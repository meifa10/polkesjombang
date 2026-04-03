<?php

namespace App\Http\Controllers\Pasien;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Barryvdh\DomPDF\Facade\Pdf;

class RekamMedisController extends Controller
{
    public function index()
    {
        $user = Auth::user();
        if (!$user) return redirect()->route('login');

        // Mengambil semua rekam medis milik pasien yang sedang login
        $rekamMedis = DB::table('rekam_medis')
            ->join('pendaftaran_poli', 'rekam_medis.pendaftaran_id', '=', 'pendaftaran_poli.id')
            ->where('pendaftaran_poli.nama_pasien', $user->name)
            ->select('rekam_medis.*', 'pendaftaran_poli.poli', 'pendaftaran_poli.nama_pasien')
            ->orderByDesc('rekam_medis.created_at')
            ->get();

        // Ambil pendaftaran terakhir untuk informasi di profil/header
        $pendaftaran = DB::table('pendaftaran_poli')
            ->where('nama_pasien', $user->name)
            ->orderByDesc('created_at')
            ->first();

        $pembayaran = null;
        if ($pendaftaran) {
            $pembayaran = DB::table('pembayaran')
                ->where('pendaftaran_id', $pendaftaran->id)
                ->first();
        }

        return view('pasien.rekammedis', compact('pendaftaran', 'rekamMedis', 'pembayaran'));
    }

    public function pdf($id)
    {
        $user = Auth::user();
        if (!$user) return redirect()->route('login');

        /**
         * PERBAIKAN DISINI: 
         * $id yang dikirim dari view adalah ID Rekam Medis.
         * Kita harus ambil data Rekam Medis dulu, baru cari Pendaftarannya.
         */
        $rmSingle = DB::table('rekam_medis')->where('id', $id)->first();

        if (!$rmSingle) {
            abort(404, 'Data Rekam Medis tidak ditemukan.');
        }

        // Ambil data pendaftaran terkait rekam medis tersebut
        $pendaftaran = DB::table('pendaftaran_poli')
            ->where('id', $rmSingle->pendaftaran_id)
            ->where('nama_pasien', $user->name)
            ->first();

        if (!$pendaftaran) {
            abort(404, 'Data Pendaftaran tidak valid.');
        }

        // Ambil riwayat rekam medis (bisa semua riwayat atau hanya satu ini saja)
        // Di sini saya ambil semua riwayat pendaftaran tersebut agar PDF lengkap
        $rekamMedis = DB::table('rekam_medis')
            ->where('pendaftaran_id', $pendaftaran->id)
            ->get();

        $pembayaran = DB::table('pembayaran')
            ->where('pendaftaran_id', $pendaftaran->id)
            ->first();

        /**
         * PASTIKAN NAMA FILE VIEW BENAR:
         * Jika nama filenya rekammedis-pdf.blade.php, maka kodenya:
         */
        $pdf = Pdf::loadView('pasien.rekammedis-pdf', [
            'pendaftaran' => $pendaftaran,
            'rekamMedis'  => $rekamMedis,
            'pembayaran'  => $pembayaran
        ]);

        $filename = 'rekam-medis-' . str_replace(' ', '-', strtolower($pendaftaran->nama_pasien)) . '.pdf';

        return $pdf->download($filename);
    }
}