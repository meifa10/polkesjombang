<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\PendaftaranPoli;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;

class PendaftaranPoliController extends Controller
{

    /**
     * ===================================================
     * SIMPAN PENDAFTARAN PASIEN JKN
     * ===================================================
     */
    public function storeJkn(Request $request)
    {
        $request->validate([
            'nama_pasien' => 'required',
            'no_identitas' => 'required',
            'tanggal_lahir' => 'required',
            'poli' => 'required'
        ]);

        /**
         * Ambil tanggal hari ini
         */
        $today = Carbon::today();

        /**
         * Ambil nomor antrian terakhir hari ini di poli yang sama
         */
        $lastQueue = PendaftaranPoli::whereDate('created_at', $today)
            ->where('poli', $request->poli)
            ->max('nomor_antrian');

        /**
         * Hitung nomor antrian berikutnya
         */
        $nomorAntrian = $lastQueue ? $lastQueue + 1 : 1;

        /**
         * Simpan pendaftaran
         */
        $pendaftaran = PendaftaranPoli::create([
            'jenis_pasien' => 'JKN',
            'nama_pasien' => $request->nama_pasien,
            'no_identitas' => Auth::user()->no_identitas,
            'tanggal_lahir' => $request->tanggal_lahir,
            'poli' => $request->poli,
            'nomor_antrian' => $nomorAntrian,
            'status' => 'menunggu'
        ]);

        /**
         * Redirect ke halaman antrian
         */
        return redirect()->route('pasien.antrian')
            ->with('success', 'Pendaftaran berhasil. Nomor antrian Anda: ' . $nomorAntrian);
    }



    /**
     * ===================================================
     * SIMPAN PENDAFTARAN PASIEN UMUM
     * ===================================================
     */
    public function storeUmum(Request $request)
    {
        $request->validate([
            'nama_pasien' => 'required',
            'no_identitas' => 'required',
            'tanggal_lahir' => 'required',
            'poli' => 'required'
        ]);

        /**
         * Ambil tanggal hari ini
         */
        $today = Carbon::today();

        /**
         * Ambil nomor antrian terakhir hari ini di poli yang sama
         */
        $lastQueue = PendaftaranPoli::whereDate('created_at', $today)
            ->where('poli', $request->poli)
            ->max('nomor_antrian');

        /**
         * Hitung nomor antrian berikutnya
         */
        $nomorAntrian = $lastQueue ? $lastQueue + 1 : 1;

        /**
         * Simpan data pendaftaran
         */
        $pendaftaran = PendaftaranPoli::create([
            'jenis_pasien' => 'UMUM',
            'nama_pasien' => $request->nama_pasien,
            'no_identitas' => Auth::user()->no_identitas,
            'tanggal_lahir' => $request->tanggal_lahir,
            'poli' => $request->poli,
            'nomor_antrian' => $nomorAntrian,
            'status' => 'menunggu'
        ]);

        /**
         * Redirect ke halaman antrian pasien
         */
        return redirect()->route('pasien.antrian')
            ->with('success', 'Pendaftaran berhasil. Nomor antrian Anda: ' . $nomorAntrian);
    }
}