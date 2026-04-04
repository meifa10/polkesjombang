<?php

namespace App\Http\Controllers\Pasien;

use App\Http\Controllers\Controller;

class PendaftaranOnlineController extends Controller
{
    public function index()
    {
        return view('pasien.pendaftaran-online');
    }
}
