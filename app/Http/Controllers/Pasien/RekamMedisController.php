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

        $querySearch = $request->input('q');
        $dateFrom = $request->input('from');
        $dateTo = $request->input('to');

        // Query Utama: Mengambil rekam medis beserta data pendaftaran, dokter, dan rincian pembayaran
        $rekamMedis = DB::table('rekam_medis')
            ->join('pendaftaran_poli', 'rekam_medis.pendaftaran_id', '=', 'pendaftaran_poli.id')
            // Join ke tabel users untuk mendapatkan nama dokter
            ->leftJoin('users as dokter', 'pendaftaran_poli.dokter_id', '=', 'dokter.id')
            // Join ke tabel pembayaran untuk ambil rincian biaya
            ->leftJoin('pembayaran', 'pendaftaran_poli.id', '=', 'pembayaran.pendaftaran_id')
            ->where('pendaftaran_poli.nama_pasien', $user->name)
            // PERBAIKAN: Hanya tampilkan rekam medis jika status pembayaran sudah 'lunas'
            ->where('pembayaran.status', 'lunas') 
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
            ->select(
                'rekam_medis.*', 
                'pendaftaran_poli.poli', 
                'dokter.name as nama_dokter', // Ambil nama dokter
                'pembayaran.total_biaya',
                'pembayaran.biaya_dokter',
                'pembayaran.total_obat',
                'pembayaran.biaya_admin',
                'pembayaran.status as status_bayar'
            )
            ->orderByDesc('rekam_medis.created_at')
            ->get();

        $pendaftaran = DB::table('pendaftaran_poli')
            ->where('nama_pasien', $user->name)
            ->orderByDesc('created_at')
            ->first();

        return view('pasien.rekammedis', compact('pendaftaran', 'rekamMedis'));
    }

    public function pdf($id)
    {
        $user = Auth::user();
        $rmSingle = DB::table('rekam_medis')->where('id', $id)->first();
        if (!$rmSingle) abort(404);

        // Ambil pendaftaran dengan join ke dokter agar nama dokter muncul di PDF
        $pendaftaran = DB::table('pendaftaran_poli')
            ->leftJoin('users as dokter', 'pendaftaran_poli.dokter_id', '=', 'dokter.id')
            ->where('pendaftaran_poli.id', $rmSingle->pendaftaran_id)
            ->where('pendaftaran_poli.nama_pasien', $user->name)
            ->select('pendaftaran_poli.*', 'dokter.name as nama_dokter') // Pastikan nama_dokter terpilih
            ->first();

        if (!$pendaftaran) abort(404);

        $pembayaran = DB::table('pembayaran')
            ->where('pendaftaran_id', $pendaftaran->id)
            ->first();

        // PERBAIKAN: Blokir akses download PDF jika pembayaran belum lunas
        if (!$pembayaran || strtolower($pembayaran->status) !== 'lunas') {
            return redirect()->back()->with('error', 'Rekam medis belum dapat diunduh karena tagihan belum dilunasi.');
        }

        $rekamMedis = DB::table('rekam_medis')
            ->where('pendaftaran_id', $pendaftaran->id)
            ->get();

          $pendaftaran->dokter = (object) ['name' => $pendaftaran->nama_dokter];

        $pdf = Pdf::loadView('pasien.rekammedis-pdf', compact('pendaftaran', 'rekamMedis', 'pembayaran'));
        return $pdf->download('rekam-medis-' . time() . '.pdf');
    }
}