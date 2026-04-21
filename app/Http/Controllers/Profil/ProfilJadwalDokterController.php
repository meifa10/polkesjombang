<?php

namespace App\Http\Controllers\Profil;

use App\Http\Controllers\Controller;
use Carbon\Carbon;

class ProfilJadwalDokterController extends Controller
{
    public function index()
    {
        $hariIni = Carbon::now()->locale('id')->translatedFormat('l'); 
        // hasil: Senin, Selasa, dst

        $dokters = [
            [
                'nama' => 'dr. Ahmad Syaikudin',
                'poli' => 'Poli Umum',
                'hari' => 'Senin – Jumat',
                'jam'  => '07.00 – 11.30',
                'foto' => 'ahmad.png',
            ],
            [
                'nama' => 'drg. Affrida Wahyu K.D',
                'poli' => 'Poli Gigi',
                'hari' => 'Senin – Jumat',
                'jam'  => '08.00 – 12.00',
                'foto' => 'affrida.png',
            ],
            [
                'nama' => 'dr. Ferry Eko Santoso',
                'poli' => 'Poli Umum',
                'hari' => 'Senin – Jumat',
                'jam'  => '11.30 – 15.30',
                'foto' => 'ferry.png',
            ],
            [
                'nama' => 'Dita Sevi A, S.Tr. Keb',
                'poli' => 'Poli KIA',
                'hari' => 'Senin – Jumat',
                'jam'  => '07.00 – 11.30',
                'foto' => 'dita.png',
            ],
            [
                'nama' => 'Nailis A, S.Tr. Keb., Bdn',
                'poli' => 'Poli KIA',
                'hari' => 'Senin – Jumat',
                'jam'  => '11.30 – 15.30',
                'foto' => 'nailis.png',
            ],
        ];

        foreach ($dokters as &$d) {
            $d['hari_ini'] = str_contains($d['hari'], $hariIni);
        }

        return view('profil.jadwal_dokter_polkes', compact('dokters'));
    }
}
