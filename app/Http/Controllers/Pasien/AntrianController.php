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

        // 1. Izinkan status 'menunggu_petugas' lolos masuk ke halaman ini
        $pendaftaran = PendaftaranPoli::where('nama_pasien', $user->name)
            ->whereIn('status', ['menunggu', 'menunggu_petugas', 'diproses_dokter'])
            ->latest()
            ->first();

        if (!$pendaftaran) {
            return redirect()->route('dashboard')->with('error', 'Antrian aktif tidak ditemukan.');
        }

        // 2. Sesuaikan hitungan orang di depan (hitung yang berstatus 'menunggu' ATAU 'menunggu_petugas')
        $antrianDiDepan = PendaftaranPoli::where('poli', $pendaftaran->poli)
            ->whereIn('status', ['menunggu', 'menunggu_petugas'])
            ->where('id', '<', $pendaftaran->id)
            ->whereDate('created_at', Carbon::today())
            ->count();

        // 3. Cek pasien yang sedang di dalam ruangan dokter
        $sedangDiPeriksa = PendaftaranPoli::where('poli', $pendaftaran->poli)
            ->where('status', 'diproses_dokter')
            ->whereDate('created_at', Carbon::today())
            ->exists();

        // 4. Logika Waktu
        if ($pendaftaran->status === 'diproses_dokter') {
            $estimasiMenit = 0;
            $prediksiWaktu = "Sekarang";
        } else {
            $tambahanWaktuSesi = $sedangDiPeriksa ? 15 : 0;
            $estimasiMenit = ($antrianDiDepan * 15) + $tambahanWaktuSesi;
            
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