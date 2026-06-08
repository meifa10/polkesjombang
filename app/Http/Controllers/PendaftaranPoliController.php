<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\PendaftaranPoli;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;

class PendaftaranPoliController extends Controller
{
    public function pendaftaranUmum()
    {
        $dokters = User::where('role', 'dokter')
            ->get(['id', 'name', 'poli', 'jam_kerja', 'hari_kerja']);
            
        return view('pasien.pendaftaran-umum', compact('dokters'));
    }

    public function storeUmum(Request $request)
    {
        $request->validate([
            'poli' => 'required|string',
            'dokter_id' => 'required|exists:users,id'
        ]);

        $today = Carbon::today();

        $dokter = User::find($request->dokter_id);
        $namaDokterAsli = $dokter ? $dokter->name : 'Dokter Tidak Diketahui';

        $antrianAktif = PendaftaranPoli::whereDate('created_at', $today)
            ->where('dokter_id', $request->dokter_id)
            ->whereIn('status', ['menunggu', 'menunggu_petugas', 'diproses_dokter'])
            ->exists();

        if (!$antrianAktif) {
            $nomorAntrian = 1;
        } else {
            $lastQueue = PendaftaranPoli::whereDate('created_at', $today)
                ->where('dokter_id', $request->dokter_id)
                ->max('nomor_antrian');

            $nomorAntrian = $lastQueue ? $lastQueue + 1 : 1;
        }

        PendaftaranPoli::create([
            'user_id'       => Auth::id(),
            'jenis_pasien'  => 'UMUM',
            'nama_pasien'   => Auth::user()->name,
            'no_identitas'  => Auth::user()->no_identitas,
            'tanggal_lahir' => Auth::user()->tanggal_lahir,
            'poli'          => $request->poli,
            'dokter_id'     => $request->dokter_id, 
            'nama_dokter'   => $namaDokterAsli, 
            'nomor_antrian' => $nomorAntrian,
            'status'        => 'menunggu_petugas'
        ]);

        return redirect()->route('pasien.antrian')
            ->with('success', "Pendaftaran berhasil! Nomor antrian Anda di {$namaDokterAsli} adalah: {$nomorAntrian}");
    }
}