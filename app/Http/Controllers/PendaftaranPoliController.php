<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\PendaftaranPoli;
use Carbon\Carbon;
use Illuminate\Support\Str; 

class PendaftaranPoliController extends Controller
{
    /**
     * ===============================
     * SIMPAN PASIEN JKN
     * ===============================
     */
    public function storeJkn(Request $request)
    {
        $request->validate([
            'nama_pasien'   => 'required|string|max:100',
            'no_identitas'  => 'required|string|max:30',
            'tanggal_lahir' => 'required|date',
            'poli'          => 'required|string|max:100',
        ]);

        // 🔥 TANGGAL HARI INI
        $today = Carbon::today();

        // 🔥 HITUNG NOMOR ANTRIAN PER HARI & PER POLI
        $lastQueue = PendaftaranPoli::whereDate('created_at', $today)
            ->where('poli', $request->poli)
            ->max('nomor_antrian');

        $nomorAntrian = $lastQueue ? $lastQueue + 1 : 1;
        $token = 'RM-' . strtoupper(Str::random(10));
        // 🔥 SIMPAN DATA
        $data = PendaftaranPoli::create([
            'jenis_pasien'  => 'jkn',
            'nama_pasien'   => $request->nama_pasien,
            'no_identitas'  => $request->no_identitas,
            'tanggal_lahir' => $request->tanggal_lahir,
            'poli'          => $request->poli,
            'nomor_antrian' => $nomorAntrian,
            'status'        => 'menunggu',
            'token_akses'   => $token,

        ]);

        // 🔥 SIMPAN ID KE SESSION (WAJIB)
        session(['antrian_id' => $data->id]);

        // 🔥 ARAHKAN KE HALAMAN ANTRIAN
        return redirect()->route('pasien.antrian');
    }

    /**
     * ===============================
     * SIMPAN PASIEN UMUM
     * ===============================
     */
    public function storeUmum(Request $request)
    {
        $request->validate([
            'nama_pasien'   => 'required|string|max:100',
            'no_identitas'  => 'required|string|max:30',
            'tanggal_lahir' => 'required|date',
            'poli'          => 'required|string|max:100',
        ]);

        // 🔥 TANGGAL HARI INI
        $today = Carbon::today();

        // 🔥 HITUNG NOMOR ANTRIAN PER HARI & PER POLI
        $lastQueue = PendaftaranPoli::whereDate('created_at', $today)
            ->where('poli', $request->poli)
            ->max('nomor_antrian');

        $nomorAntrian = $lastQueue ? $lastQueue + 1 : 1;
        $token = 'RM-' . strtoupper(Str::random(10));
        // 🔥 SIMPAN DATA
        $data = PendaftaranPoli::create([
            'jenis_pasien'  => 'umum',
            'nama_pasien'   => $request->nama_pasien,
            'no_identitas'  => $request->no_identitas,
            'tanggal_lahir' => $request->tanggal_lahir,
            'poli'          => $request->poli,
            'nomor_antrian' => $nomorAntrian,
            'status'        => 'menunggu',
            'token_akses'   => $token,
        ]);

        // 🔥 SIMPAN ID KE SESSION
        session(['antrian_id' => $data->id]);

        return redirect()->route('pasien.antrian');
    }
}
