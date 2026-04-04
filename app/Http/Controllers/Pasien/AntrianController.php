<?php

namespace App\Http\Controllers\Pasien;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use App\Models\PendaftaranPoli;
use Carbon\Carbon;

class AntrianController extends Controller
{
    /**
     * Menampilkan halaman nomor antrian pasien.
     */
    public function index()
    {
        // 1. Ambil data user yang sedang login
        $user = Auth::user();

        // Keamanan: Jika session hilang atau user tidak login
        if (!$user) {
            return redirect()->route('login');
        }

        /**
         * 2. Ambil pendaftaran TERAKHIR milik user ini.
         * Penjelasan Query:
         * - where('user_id', $user->id) : Mencari ID pasien yang login (ID 9)
         * - where('status', '!=', 'selesai') : Pastikan statusnya belum 'selesai'
         * - latest('id') : Menggunakan ID paling besar (paling baru) agar lebih akurat dari created_at
         */
        $pendaftaran = PendaftaranPoli::where('user_id', $user->id)
            ->where('status', '!=', 'selesai')
            ->latest('id') 
            ->first();

        /**
         * TIPS DEBUGGING:
         * Jika halaman tetap kosong, hapus tanda // pada kode di bawah ini 
         * untuk melihat apakah data ditemukan atau tidak:
         */
        // dd($pendaftaran); 

        // 3. Kirim ke View
        return view('pasien.antrian', [
            'data' => $pendaftaran
        ]);
    }
}