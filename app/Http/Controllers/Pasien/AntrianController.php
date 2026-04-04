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
     * Kriteria: Milik user login, belum selesai, dan belum lewat 24 jam.
     */
    public function index()
    {
        // 1. Pastikan user sudah login (Safety Check)
        if (!Auth::check()) {
            return redirect()->route('login');
        }

        $user = Auth::user();

        /**
         * 2. Cari data pendaftaran dengan kriteria ketat:
         * - 'user_id'    : Mencocokkan dengan ID user yang sedang login.
         * - 'status'     : Menggunakan tanda kutip 'selesai' agar tidak terbaca sebagai kolom.
         * - 'created_at' : Menggunakan Carbon untuk limitasi waktu 24 jam (subDay).
         */
        $pendaftaran = PendaftaranPoli::where('user_id', $user->id)
            ->where('status', '!=', 'selesai') 
            // ->where('created_at', '>=', Carbon::now()->subDay()) 
            ->latest('created_at') // Urutkan berdasarkan waktu pendaftaran terbaru
            ->first();

        /**
         * 3. Kirim data ke view 'pasien.antrian'.
         * Variabel 'data' akan berisi NULL jika tidak ada antrian yang memenuhi syarat.
         */
        return view('pasien.antrian', [
            'data' => $pendaftaran
        ]);
    }
}