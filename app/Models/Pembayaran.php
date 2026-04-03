<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Pembayaran extends Model
{
    protected $table = 'pembayaran';

    protected $fillable = [
        'pendaftaran_id',
        'total_biaya',
        'metode',
        'status',
        'payment_ref',
        'paid_by',
        'tanggal_bayar'
    ];

    public function pendaftaran()
    {
        return $this->belongsTo(PendaftaranPoli::class,'pendaftaran_id');
    }
}