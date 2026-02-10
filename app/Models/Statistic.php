<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Statistic extends Model
{
    protected $table = 'statistics';

    protected $fillable = [
        'kamar',
        'poli',
        'dokter',
        'karyawan'
    ];
}
