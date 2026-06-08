<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Models\Pembayaran;
use App\Models\RekamMedis;
use App\Models\User;

class PendaftaranPoli extends Model
{
    protected $table = 'pendaftaran_poli';

    protected $fillable = [
        'user_id',
        'jenis_pasien',
        'nama_pasien',
        'no_identitas',
        'tanggal_lahir',
        'poli',
        'dokter_id',
        'nama_dokter',
        'nomor_antrian',
        'status',
        'token_akses'
    ];

    public $timestamps = true;

    public function dokter()
    {
        return $this->belongsTo(User::class, 'dokter_id');
    }

    public function pembayaran()
    {
        return $this->hasOne(Pembayaran::class, 'pendaftaran_id');
    }

    public function rekamMedis()
    {
        return $this->hasOne(RekamMedis::class, 'pendaftaran_id');
    }
}