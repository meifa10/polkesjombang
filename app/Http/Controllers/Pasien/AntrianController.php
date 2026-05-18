<?php

namespace App\Http\Controllers\Pasien;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use App\Models\PendaftaranPoli;
use Carbon\Carbon;

class AntrianController extends Controller
{
    public function index()
    {
        $user = Auth::user();

        // 1. Ambil antrian aktif terbaru milik user ini (yang statusnya belum selesai)
        $pendaftaran = PendaftaranPoli::where('nama_pasien', $user->name)
            ->whereIn('status', ['menunggu', 'diproses_dokter'])
            ->latest()
            ->first();

        // Proteksi: Jika tidak ada antrian aktif, redirect ke dashboard
        if (!$pendaftaran) {
            return redirect()->route('dashboard')->with('error', 'Antrian aktif tidak ditemukan.');
        }

        // 2. HITUNG ANTRIAN REAL-TIME DI DEPAN PASIEN (Hanya poli yang sama & hari yang sama)
        // Pasien di depan adalah mereka yang mendaftar lebih dulu (ID lebih kecil) dan statusnya masih 'menunggu'
        $antrianDiDepan = PendaftaranPoli::where('poli', $pendaftaran->poli)
            ->where('status', 'menunggu')
            ->where('id', '<', $pendaftaran->id)
            ->whereDate('created_at', Carbon::today())
            ->count();

        // 3. CEK APAKAH ADA PASIEN YANG SEDANG DIPROSES DI DALAM POLI SEKARANG
        $sedangDiPeriksa = PendaftaranPoli::where('poli', $pendaftaran->poli)
            ->where('status', 'diproses_dokter')
            ->whereDate('created_at', Carbon::today())
            ->exists();

        // 4. LOGIKA ESTIMASI WAKTU REAL-TIME:
        // Jika status pasien sendiri sudah 'diproses_dokter', artinya dia sedang dipanggil/diperiksa (0 menit tunggu)
        if ($pendaftaran->status === 'diproses_dokter') {
            $estimasiMenit = 0;
            $prediksiWaktu = "Sekarang";
        } else {
            // Jika ada pasien lain di dalam poli yang sedang diperiksa, sisa waktunya kita asumsikan berjalan.
            // Rumus: (Jumlah orang mengantre di depan * 15 menit) + 15 menit (untuk menyelesaikan pasien yang sedang di dalam ruangan)
            $tambahanWaktuSesi = $sedangDiPeriksa ? 15 : 0;
            $estimasiMenit = ($antrianDiDepan * 15) + $tambahanWaktuSesi;
            
            // Jika estimasi 0 menit (karena tidak ada orang di depan dan poli kosong), dia akan langsung dilayani
            if ($estimasiMenit === 0) {
                $prediksiWaktu = "Silahkan Menuju Poli";
            } else {
                $prediksiWaktu = '± ' . Carbon::now()->addMinutes($estimasiMenit)->format('H:i') . ' WIB';
            }
        }

        return view('pasien.antrian', [
            'data' => $pendaftaran,
            'antrianDiDepan' => $antrianDiDepan,
            'prediksi' => $prediksiWaktu
        ]);
    }
}