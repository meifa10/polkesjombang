<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon;

class JadwalDokter extends Model
{
    protected $table = 'jadwal_dokter';

    protected $fillable = [
        'dokter_id',
        'poli',
        'hari',
        'jam_mulai',
        'jam_selesai',
        'status'
    ];

    protected $appends = ['is_active_now'];

    public function dokter()
    {
        return $this->belongsTo(User::class, 'dokter_id');
    }

    /**
     * ==========================
     * CEK APAKAH SEDANG PRAKTIK
     * ==========================
     */
    public function getIsActiveNowAttribute(): bool
    {
        if ($this->status !== 'aktif') {
            return false;
        }

        $now = Carbon::now();

        // cek hari
        $hariIni = $now->locale('id')->isoFormat('dddd');
        $hari = array_map('trim', explode(',', $this->hari));

        if (!in_array($hariIni, $hari)) {
            return false;
        }

        // 🔥 PARSING JAM PALING AMAN (APAPUN FORMAT DB)
        try {
            $mulai = Carbon::parse($this->jam_mulai)
                ->setDateFrom($now);

            $selesai = Carbon::parse($this->jam_selesai)
                ->setDateFrom($now);
        } catch (\Exception $e) {
            return false; // kalau jam rusak, jangan crash
        }

        return $now->between($mulai, $selesai);
    }
}
