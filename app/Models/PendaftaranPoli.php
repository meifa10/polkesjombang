<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Models\Pembayaran;
use App\Models\RekamMedis;

class PendaftaranPoli extends Model
{
    /**
     * Nama tabel
     */
    protected $table = 'pendaftaran_poli';

    /**
     * Field yang boleh diisi
     */
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

    /**
     * Pastikan timestamps aktif
     * agar created_at tersimpan otomatis
     */
    public $timestamps = true;

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