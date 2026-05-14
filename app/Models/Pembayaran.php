<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Models\PendaftaranPoli;

class Pembayaran extends Model
{
    protected $table = 'pembayaran';

    protected $fillable = [

        'pendaftaran_id',

        'total_obat',

        'biaya_dokter',

        'biaya_admin',

        'total_biaya',

        'metode',

        'status',

        'payment_ref',

        'snap_token',

        'paid_by',

        'tanggal_bayar'
    ];


    /*
    |--------------------------------------------------------------------------
    | RELASI KE PENDAFTARAN
    |--------------------------------------------------------------------------
    */

    public function pendaftaran()
    {
        return $this->belongsTo(
            PendaftaranPoli::class,
            'pendaftaran_id'
        );
    }


    /*
    |--------------------------------------------------------------------------
    | FORMAT STATUS
    |--------------------------------------------------------------------------
    */

    public function getStatusBadgeAttribute()
    {

        if ($this->status == 'pending') {

            return 'warning';

        } elseif ($this->status == 'lunas') {

            return 'success';

        } elseif ($this->status == 'gagal') {

            return 'danger';
        }

        return 'secondary';
    }


    /*
    |--------------------------------------------------------------------------
    | FORMAT TOTAL RUPIAH
    |--------------------------------------------------------------------------
    */

    public function getFormatTotalAttribute()
    {
        return 'Rp '
            . number_format(
                $this->total_biaya,
                0,
                ',',
                '.'
            );
    }
}