<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Patient extends Model
{
    use HasFactory;

    protected $table = 'patients';

    protected $fillable = [
        'user_id',
        'nik',
        'tanggal_lahir',
        'jenis_kelamin',
        'alamat',
        'no_hp',
    ];

    /**
     * Relasi ke tabel users
     * 1 pasien = 1 user
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
