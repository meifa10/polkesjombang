<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Models\Pembayaran;
use App\Models\RekamMedis;
use App\Models\User;

class PendaftaranPoli extends Model
{
    /**
     * Nama tabel sesuai database hosting
     */
    protected $table = 'pendaftaran_poli';

    /**
     * Field yang boleh diisi (Mass Assignment)
     */
    protected $fillable = [
        'user_id',
        'jenis_pasien',
        'nama_pasien',
        'no_identitas',
        'tanggal_lahir',
        'poli',
        'nomor_antrian',
        'status',
        'token_akses'
    ];

    /**
     * Timestamps aktif untuk created_at & updated_at
     */
    public $timestamps = true;

    /**
     * Relasi ke User (Pemilik Antrian)
     */
    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    /**
     * Relasi ke tabel pembayaran
     */
    public function pembayaran()
    {
        return $this->hasOne(Pembayaran::class, 'pendaftaran_id');
    }

    /**
     * Relasi ke rekam medis
     */
    public function rekamMedis()
    {
        return $this->hasOne(RekamMedis::class, 'pendaftaran_id');
    }
}