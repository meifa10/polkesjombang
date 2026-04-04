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
        // 1. Ambil data user yang sedang login
        $user = Auth::user();

        /**
         * 2. Cari data pendaftaran dengan kriteria:
         * - user_id sesuai dengan pasien yang login
         * - status BUKAN 'selesai' (berarti belum bayar/diperiksa)
         * - dibuat dalam 24 jam terakhir (created_at >= 24 jam yang lalu)
         */
        $pendaftaran = PendaftaranPoli::where('user_id', $user->id)
            ->where('status', '!=', 'selesai')
            ->where('created_at', '>=', Carbon::now()->subDay()) 
            ->latest() // Ambil yang paling baru jika ada double input
            ->first();

        /**
         * 3. Kirim ke view. 
         * Jika $pendaftaran kosong (null), maka Blade akan otomatis 
         * menampilkan pesan "Nomor Antrian Tidak Tersedia".
         */
        return view('pasien.antrian', [
            'data' => $pendaftaran
        ]);
    }
}