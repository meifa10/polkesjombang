<?php

namespace App\Http\Controllers\Pasien;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\PendaftaranPoli;

class DashboardController extends Controller
{
    public function index()
    {
        /**
         * Ambil pendaftaran terakhir pasien
         * Cocokkan dengan no_identitas
         * (sesuai data yang diinput saat daftar)
         */
        $kunjungan = PendaftaranPoli::where('no_identitas', auth()->user()->no_identitas ?? null)
            ->latest()
            ->first();

        return view('pasien.dashboard', compact('kunjungan'));
    }
}
