<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PendaftaranPoli extends Model
{
    protected $table = 'pendaftaran_poli';

    protected $fillable = [
        'jenis_pasien',
        'nama_pasien',
        'no_identitas',
        'tanggal_lahir',
        'poli',
        'nomor_antrian',
        'status',
        'token_akses'
    ];

}
