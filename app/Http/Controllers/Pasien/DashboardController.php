<?php

namespace App\Http\Controllers\Pasien;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;
use App\Models\PendaftaranPoli;
use App\Models\Pembayaran;
use App\Models\RekamMedis;
use Carbon\Carbon;

class DashboardController extends Controller
{
    public function index(Request $request)
    {
        /*
        =====================================
        CEK LOGIN USER
        =====================================
        */
        if (!Auth::check()) {
            return redirect()->route('login');
        }

        $user = Auth::user();
        
        // Menangkap filter dari request
        $bulanFilter = $request->get('bulan');
        $poliFilter = $request->get('poli');

        /*
        =====================================
        AKTIVITAS TERAKHIR PASIEN
        Mengambil riwayat pendaftaran poli
        =====================================
        */
        $queryKunjungan = PendaftaranPoli::where('nama_pasien', $user->name);

        // Filter berdasarkan Bulan (jika dipilih)
        if (!empty($bulanFilter)) {
            $queryKunjungan->whereMonth('created_at', $bulanFilter);
        }

        // Filter berdasarkan Poli (jika dipilih)
        if (!empty($poliFilter)) {
            $queryKunjungan->where('poli', $poliFilter);
        }

        $kunjungan = $queryKunjungan
            ->orderBy('id', 'desc')
            ->limit(10)
            ->get();

        /*
        =====================================
        REKAM MEDIS TERAKHIR PASIEN
        =====================================
        */
        $rekamMedis = RekamMedis::join(
                'pendaftaran_poli',
                'rekam_medis.pendaftaran_id',
                '=',
                'pendaftaran_poli.id'
            )
            ->where('pendaftaran_poli.nama_pasien', $user->name)
            ->select(
                'rekam_medis.*',
                'pendaftaran_poli.poli',
                'pendaftaran_poli.created_at as tanggal_kunjungan'
            )
            ->orderBy('rekam_medis.id', 'desc')
            ->limit(5)
            ->get();

        /*
        =====================================
        ANTRIAN AKTIF HARI INI
        =====================================
        */
        $antrianAktif = PendaftaranPoli::where('nama_pasien', $user->name)
            ->whereDate('created_at', Carbon::today())
            ->whereIn('status', ['menunggu', 'proses'])
            ->orderBy('id', 'desc')
            ->first();

        /*
        =====================================
        PEMBAYARAN YANG BELUM LUNAS
        =====================================
        */
        $pembayaran = Pembayaran::join(
                'pendaftaran_poli',
                'pembayaran.pendaftaran_id',
                '=',
                'pendaftaran_poli.id'
            )
            ->where('pendaftaran_poli.nama_pasien', $user->name)
            ->where('pembayaran.status', 'belum_lunas')
            ->select('pembayaran.*')
            ->orderBy('pembayaran.id', 'desc')
            ->first();

        /*
        =====================================
        KIRIM DATA KE VIEW
        =====================================
        */
        return view('pasien.dashboard', [
            'user' => $user,
            'kunjungan' => $kunjungan,
            'rekamMedis' => $rekamMedis,
            'antrianAktif' => $antrianAktif,
            'pembayaran' => $pembayaran
        ]);
    }
}