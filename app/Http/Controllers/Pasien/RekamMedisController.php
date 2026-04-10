<?php

namespace App\Http\Controllers\Pasien;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Barryvdh\DomPDF\Facade\Pdf;

class RekamMedisController extends Controller
{
    public function index(Request $request)
    {
        $user = Auth::user();
        if (!$user) return redirect()->route('login');

        // Mengambil data input dari filter di View
        $querySearch = $request->input('q');
        $dateFrom = $request->input('from');
        $dateTo = $request->input('to');

        // Mengambil rekam medis dengan filter
        $rekamMedis = DB::table('rekam_medis')
            ->join('pendaftaran_poli', 'rekam_medis.pendaftaran_id', '=', 'pendaftaran_poli.id')
            ->where('pendaftaran_poli.nama_pasien', $user->name)
            ->when($querySearch, function ($query, $querySearch) {
                return $query->where(function($q) use ($querySearch) {
                    $q->where('rekam_medis.diagnosis', 'like', '%' . $querySearch . '%')
                      ->orWhere('rekam_medis.resep', 'like', '%' . $querySearch . '%');
                });
            })
            ->when($dateFrom, function ($query, $dateFrom) {
                return $query->whereDate('rekam_medis.created_at', '>=', $dateFrom);
            })
            ->when($dateTo, function ($query, $dateTo) {
                return $query->whereDate('rekam_medis.created_at', '<=', $dateTo);
            })
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

        // Ambil data Rekam Medis berdasarkan ID
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

        // Ambil riwayat rekam medis (hanya satu ini saja untuk PDF satuan)
        $rekamMedis = DB::table('rekam_medis')
            ->where('pendaftaran_id', $pendaftaran->id)
            ->get();

        $pembayaran = DB::table('pembayaran')
            ->where('pendaftaran_id', $pendaftaran->id)
            ->first();

        // Load View PDF
        $pdf = Pdf::loadView('pasien.rekammedis-pdf', [
            'pendaftaran' => $pendaftaran,
            'rekamMedis'  => $rekamMedis,
            'pembayaran'  => $pembayaran
        ]);

        // Berikan nama file PDF yang unik berdasarkan nama pasien dan tanggal
        $filename = 'rekam-medis-' . str_replace(' ', '-', strtolower($pendaftaran->nama_pasien)) . '-' . date('Ymd') . '.pdf';

        return $pdf->download($filename);
    }
}