<?php

namespace App\Http\Controllers\Pasien;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\PendaftaranPoli;
use App\Models\RekamMedis;
use App\Models\Pembayaran;
use Barryvdh\DomPDF\Facade\Pdf;

class RekamMedisController extends Controller
{
    /**
     * =====================================
     * TAMPILKAN REKAM MEDIS BERDASARKAN TOKEN
     * =====================================
     */
    public function index(Request $request)
    {
        $token = $request->query('token');

        // 1️⃣ Token wajib ada
        if (!$token) {
            return view('pasien.rekammedis-token', [
                'error' => 'Token tidak boleh kosong.'
            ]);
        }

        // 2️⃣ Cari pendaftaran
        $pendaftaran = PendaftaranPoli::where('token_akses', $token)->first();

        if (!$pendaftaran) {
            return view('pasien.rekammedis-token', [
                'error' => 'Token tidak valid atau tidak ditemukan.'
            ]);
        }

        // 3️⃣ Ambil rekam medis
        $rekamMedis = RekamMedis::where('pendaftaran_id', $pendaftaran->id)
            ->orderByDesc('created_at')
            ->get();

        // 4️⃣ Ambil pembayaran (BISA NULL)
        $pembayaran = Pembayaran::where('pendaftaran_id', $pendaftaran->id)
            ->first();

        return view('pasien.rekammedis', [
            'pendaftaran' => $pendaftaran,
            'rekamMedis'  => $rekamMedis,
            'pembayaran'  => $pembayaran,
        ]);
    }

    /**
     * ==============================
     * DOWNLOAD REKAM MEDIS (PDF)
     * ==============================
     */
    public function pdf(string $token)
    {
        // 1️⃣ Ambil pendaftaran dari token
        $pendaftaran = PendaftaranPoli::where('token_akses', $token)
            ->firstOrFail();

        // 2️⃣ Ambil rekam medis
        $rekamMedis = RekamMedis::where('pendaftaran_id', $pendaftaran->id)
            ->orderByDesc('created_at')
            ->get();

        // 3️⃣ Ambil pembayaran (opsional)
        $pembayaran = \App\Models\Pembayaran::where(
            'pendaftaran_id',
            $pendaftaran->id
        )->first();

        // 4️⃣ Load PDF
        $pdf = Pdf::loadView(
            'pasien.rekammedis-pdf',
            compact('pendaftaran', 'rekamMedis', 'pembayaran')
        );

        return $pdf->download(
            'rekam-medis-' .
            str_replace(' ', '-', strtolower($pendaftaran->nama_pasien)) .
            '.pdf'
        );
    }

}
