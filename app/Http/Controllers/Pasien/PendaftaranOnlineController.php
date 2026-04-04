<?php

namespace App\Http\Controllers\Pasien;

use App\Http\Controllers\Controller;
use App\Models\PendaftaranPoli;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;

class PendaftaranOnlineController extends Controller
{
    public function index()
    {
        return view('pasien.pendaftaran-online');
    }

    public function store(Request $request)
    {
        // 1. Validasi input dari form
        $request->validate([
            'nama_pasien'   => 'required|string|max:255',
            'no_identitas'  => 'required|string',
            'poli'          => 'required|string',
            'jenis_pasien'  => 'required|string',
            'tanggal_lahir' => 'required|date',
        ]);

        // 2. Generate Nomor Antrian otomatis berdasarkan Poli hari ini
        // Menghitung berapa orang yang sudah daftar di poli yang sama hari ini
        $cekAntrianHariIni = PendaftaranPoli::where('poli', $request->poli)
            ->whereDate('created_at', now())
            ->count();
        
        $nomorBaru = $cekAntrianHariIni + 1;

        // 3. Simpan data ke Database
        $simpan = PendaftaranPoli::create([
            'user_id'       => Auth::id(), // Mengunci antrian ke akun pengirim
            'nama_pasien'   => $request->nama_pasien,
            'jenis_pasien'  => $request->jenis_pasien,
            'no_identitas'  => $request->no_identitas,
            'tanggal_lahir' => $request->tanggal_lahir,
            'poli'          => $request->poli,
            'nomor_antrian' => $nomorBaru,
            'status'        => 'menunggu', // Status default saat baru daftar
            'token_akses'   => strtoupper(Str::random(6)), // Token unik rekam medis
        ]);

        // 4. Redirect langsung ke halaman tiket antrian
        if ($simpan) {
            return redirect()->route('pasien.antrian.index')
                             ->with('success', 'Pendaftaran Berhasil! Silahkan simpan nomor antrian Anda.');
        }

        return back()->with('error', 'Gagal melakukan pendaftaran, coba lagi.');
    }
}