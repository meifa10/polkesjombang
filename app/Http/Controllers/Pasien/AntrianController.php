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

        // Mengambil antrian terbaru milik user ini
        $pendaftaran = PendaftaranPoli::where('nama_pasien', $user->name) // Sesuaikan field pencarian user Anda
            ->latest()
            ->first();

        // Proteksi: Jika tidak ada antrian atau statusnya sudah selesai, 
        // maka tidak boleh melihat halaman ini (redirect ke dashboard)
        if (!$pendaftaran || $pendaftaran->status === 'selesai') {
            return redirect()->route('dashboard')->with('error', 'Antrian aktif tidak ditemukan.');
        }

        // LOGIKA PREDIKSI WAKTU:
        // Hitung pasien dengan poli yang sama, di hari yang sama, yang mendaftar lebih dulu dan masih 'menunggu'
        $antrianDiDepan = PendaftaranPoli::where('poli', $pendaftaran->poli)
            ->where('status', 'menunggu')
            ->where('id', '<', $pendaftaran->id) // Berdasarkan ID yang lebih kecil (lebih dulu)
            ->whereDate('created_at', Carbon::today())
            ->count();

        // Asumsi 1 pasien dilayani selama 15 menit
        $estimasiMenit = ($antrianDiDepan + 1) * 15;
        $prediksiWaktu = Carbon::parse($pendaftaran->created_at)->addMinutes($estimasiMenit)->format('H:i');

        return view('pasien.antrian', [
            'data' => $pendaftaran,
            'prediksi' => $prediksiWaktu
        ]);
    }
}