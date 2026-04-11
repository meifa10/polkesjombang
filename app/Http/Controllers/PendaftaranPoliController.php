<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\PendaftaranPoli;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;

class PendaftaranPoliController extends Controller
{
    /**
     * SIMPAN PENDAFTARAN PASIEN JKN
     */
    public function storeJkn(Request $request)
    {
        $request->validate([
            'nama_pasien' => 'required',
            'no_identitas' => 'required',
            'tanggal_lahir' => 'required',
            'poli' => 'required'
        ]);

        $today = Carbon::today();
        $lastQueue = PendaftaranPoli::whereDate('created_at', $today)
            ->where('poli', $request->poli)
            ->max('nomor_antrian');

        $nomorAntrian = $lastQueue ? $lastQueue + 1 : 1;

        // PERBAIKAN: Gunakan $request->no_identitas agar data masuk ke DB
        $pendaftaran = PendaftaranPoli::create([
            'jenis_pasien' => 'JKN',
            'nama_pasien' => $request->nama_pasien,
            'no_identitas' => $request->no_identitas, 
            'tanggal_lahir' => $request->tanggal_lahir,
            'poli' => $request->poli,
            'nomor_antrian' => $nomorAntrian,
            'status' => 'menunggu'
        ]);

        return redirect()->route('pasien.antrian')
            ->with('success', 'Pendaftaran berhasil. Nomor antrian Anda: ' . $nomorAntrian);
    }

    /**
     * SIMPAN PENDAFTARAN PASIEN UMUM
     */
    public function storeUmum(Request $request)
    {
        $request->validate([
            'nama_pasien' => 'required',
            'no_identitas' => 'required',
            'tanggal_lahir' => 'required',
            'poli' => 'required'
        ]);

        $today = Carbon::today();
        $lastQueue = PendaftaranPoli::whereDate('created_at', $today)
            ->where('poli', $request->poli)
            ->max('nomor_antrian');

        $nomorAntrian = $lastQueue ? $lastQueue + 1 : 1;

        // PERBAIKAN: Gunakan $request->no_identitas
        $pendaftaran = PendaftaranPoli::create([
            'jenis_pasien' => 'UMUM',
            'nama_pasien' => $request->nama_pasien,
            'no_identitas' => $request->no_identitas, 
            'tanggal_lahir' => $request->tanggal_lahir,
            'poli' => $request->poli,
            'nomor_antrian' => $nomorAntrian,
            'status' => 'menunggu'
        ]);

        return redirect()->route('pasien.antrian')
            ->with('success', 'Pendaftaran berhasil. Nomor antrian Anda: ' . $nomorAntrian);
    }
}