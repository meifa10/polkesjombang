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
        |--------------------------------------------------------------------------
        | CEK LOGIN
        |--------------------------------------------------------------------------
        */
        if (!Auth::check()) {
            return redirect()->route('login');
        }

        $user = Auth::user();

        /*
        |--------------------------------------------------------------------------
        | FILTER (Bulan & Poli)
        |--------------------------------------------------------------------------
        */
        $bulanFilter = $request->get('bulan');
        $poliFilter = $request->get('poli');

        /*
        |--------------------------------------------------------------------------
        | DATA KUNJUNGAN (Aktivitas Terakhir)
        |--------------------------------------------------------------------------
        | Bagian ini mengambil riwayat pendaftaran. Jika pembayaran sukses, 
        | status di sini akan berubah menjadi 'selesai'.
        */
        $queryKunjungan = PendaftaranPoli::where('nama_pasien', $user->name);

        if (!empty($bulanFilter)) {
            $queryKunjungan->whereMonth('created_at', $bulanFilter);
        }

        if (!empty($poliFilter)) {
            $queryKunjungan->where('poli', $poliFilter);
        }

        $kunjungan = $queryKunjungan
            ->orderBy('id', 'desc')
            ->limit(10)
            ->get();

        /*
        |--------------------------------------------------------------------------
        | REKAM MEDIS
        |--------------------------------------------------------------------------
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
        |--------------------------------------------------------------------------
        | ANTREAN AKTIF
        |--------------------------------------------------------------------------
        | Kita hanya mengambil pendaftaran yang BELUM selesai hari ini.
        */
        $antrianAktif = PendaftaranPoli::where('nama_pasien', $user->name)
            ->whereDate('created_at', Carbon::today())
            ->whereIn('status', [
                'menunggu_admin',
                'diproses_dokter',
                'menunggu_pembayaran' // Tambahkan status ini jika perlu
            ])
            ->orderBy('id', 'desc')
            ->first();

        /*
        |--------------------------------------------------------------------------
        | PEMBAYARAN AKTIF
        |--------------------------------------------------------------------------
        | Logika: Kita mencari record di tabel pembayaran yang statusnya 'pending' 
        | atau 'gagal'. Jika transaksi di Midtrans sudah 'lunas', maka record 
        | tersebut tidak akan terpilih (menjadi null). 
        | Di View, jika $pembayaran == null, maka menu pembayaran otomatis mati.
        */
        $pembayaran = Pembayaran::join(
                'pendaftaran_poli',
                'pembayaran.pendaftaran_id',
                '=',
                'pendaftaran_poli.id'
            )
            ->where('pendaftaran_poli.nama_pasien', $user->name)
            ->whereIn('pembayaran.status', ['pending', 'gagal']) 
            ->select('pembayaran.*')
            ->orderBy('pembayaran.id', 'desc')
            ->first();

        /*
        |--------------------------------------------------------------------------
        | RETURN VIEW
        |--------------------------------------------------------------------------
        */
        return view('pasien.dashboard', [
            'user' => $user,
            'kunjungan' => $kunjungan,
            'rekamMedis' => $rekamMedis,
            'antrianAktif' => $antrianAktif,
            'pembayaran' => $pembayaran, // Jika null, tombol di UI akan otomatis non-aktif
        ]);
    }
}