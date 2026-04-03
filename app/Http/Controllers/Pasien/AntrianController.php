<?php

namespace App\Http\Controllers\Pasien;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use App\Models\PendaftaranPoli;

class AntrianController extends Controller
{
    public function index()
    {
        $user = Auth::user();

        $pendaftaran = PendaftaranPoli::latest()->first();

        return view('pasien.antrian', [
            'data' => $pendaftaran
        ]);
    }
}