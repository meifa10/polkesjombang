<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\PendaftaranPoli;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;

class PendaftaranPoliController extends Controller
{

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