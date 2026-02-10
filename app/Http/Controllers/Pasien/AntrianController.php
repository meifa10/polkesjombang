<?php

namespace App\Http\Controllers\Pasien;

use App\Http\Controllers\Controller;
use App\Models\PendaftaranPoli;

class AntrianController extends Controller
{
    public function index()
    {
        $id = session('antrian_id');

        if (!$id) {
            return redirect('/')->with('error', 'Antrian tidak ditemukan.');
        }

        $data = PendaftaranPoli::findOrFail($id);

        return view('pasien.antrian', compact('data'));
    }
}
