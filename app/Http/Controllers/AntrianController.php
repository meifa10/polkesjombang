<?php
namespace App\Http\Controllers;

use App\Models\PendaftaranPoli;

class AntrianController extends Controller
{
    public function index()
    {
        $data = PendaftaranPoli::whereNotNull('token_akses')
            ->latest()
            ->first();

        return view('pasien.antrian', compact('data'));
    }
}
