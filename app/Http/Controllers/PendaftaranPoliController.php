<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\PendaftaranPoli;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;

class PendaftaranPoliController extends Controller
{
    // Tampilkan Form Pendaftaran
    public function pendaftaranUmum()
    {
        // Ambil data dokter dengan kolom tambahan jam & hari kerja
        $dokters = User::where('role', 'dokter')
            ->get(['id', 'name', 'poli', 'jam_kerja', 'hari_kerja']);
            
        return view('pasien.pendaftaran-umum', compact('dokters'));
    }

    // Simpan Pendaftaran
    public function storeUmum(Request $request)
    {
        $request->validate([
            'poli' => 'required|string',
            'dokter_id' => 'required|exists:users,id'
        ]);

        $today = Carbon::today();

        // Hitung nomor antrian real-time berdasarkan poli hari ini
        // Menggunakan lockForUpdate() disarankan jika traffic sangat tinggi
        $lastQueue = PendaftaranPoli::whereDate('created_at', $today)
            ->where('poli', $request->poli)
            ->max('nomor_antrian');

        $nomorAntrian = $lastQueue ? $lastQueue + 1 : 1;

        PendaftaranPoli::create([
            'user_id'       => Auth::id(),
            'jenis_pasien'  => 'UMUM',
            'nama_pasien'   => Auth::user()->name,
            'no_identitas'  => Auth::user()->no_identitas,
            'tanggal_lahir' => Auth::user()->tanggal_lahir,
            'poli'          => $request->poli,
            'dokter_id'     => $request->dokter_id, 
            'nomor_antrian' => $nomorAntrian,
            'status'        => 'menunggu_petugas'
        ]);

        return redirect()->route('pasien.antrian')
            ->with('success', "Pendaftaran berhasil! Nomor antrian Anda di {$request->poli} adalah: {$nomorAntrian}");
    }
}