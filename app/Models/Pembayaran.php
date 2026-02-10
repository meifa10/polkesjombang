<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Pembayaran extends Model
{
    protected $table = 'pembayaran';

    protected $fillable = [
        'pendaftaran_id',
        'total_biaya',
        'status',
        'metode',
        'tanggal_bayar'
    ];

    public $timestamps = true;

    public function pendaftaran()
    {
        return $this->belongsTo(PendaftaranPoli::class, 'pendaftaran_id');
    }
}
