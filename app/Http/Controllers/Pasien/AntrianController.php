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
     * Logika: Mencari pendaftaran terbaru milik user yang belum diselesaikan.
     */
    public function index()
    {
        // 1. Ambil data user yang sedang login
        $user = Auth::user();

        // Jika karena suatu alasan session hilang, arahkan ke login
        if (!$user) {
            return redirect()->route('login');
        }

        /**
         * 2. Ambil pendaftaran TERAKHIR milik user ini.
         * Kita filter berdasarkan user_id dan status.
         * Kita gunakan 'selesai' sebagai string agar database tidak bingung.
         */
        $pendaftaran = PendaftaranPoli::where('user_id', $user->id)
            ->where('status', '!=', 'selesai')
            ->latest('created_at')
            ->first();

        /**
         * 3. Kirim ke View.
         * Tips: Jika di browser tetap muncul "Tidak Tersedia", 
         * coba buka tab baru atau hapus cache browser.
         */
        return view('pasien.antrian', [
            'data' => $pendaftaran
        ]);
    }
}