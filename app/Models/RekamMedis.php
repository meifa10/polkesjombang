<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class RekamMedis extends Model
{
    use HasFactory;

    protected $table = 'rekam_medis';

    protected $fillable = [
        'pendaftaran_id',
        'dokter_id',
        'keluhan',
        'diagnosis',
        'tindakan',
        'resep',
    ];

    public function pendaftaran()
    {
        return $this->belongsTo(PendaftaranPoli::class,'pendaftaran_id');
    }

    public function dokter()
    {
        return $this->belongsTo(User::class,'dokter_id');
    }
}