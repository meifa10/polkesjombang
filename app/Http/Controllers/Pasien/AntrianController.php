<?php

namespace App\Http\Controllers\Pasien;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use App\Models\PendaftaranPoli;
use Carbon\Carbon;

class AntrianController extends Controller
{
    public function index()
    {
        $user = Auth::user();

        $pendaftaran = PendaftaranPoli::where('nama_pasien', $user->name)
            ->whereIn('status', ['menunggu', 'menunggu_petugas', 'diproses_dokter'])
            ->latest()
            ->first();

        if (!$pendaftaran) {
            return redirect()->route('dashboard')->with('error', 'Antrian aktif tidak ditemukan.');
        }

        $waktuDaftar = Carbon::parse($pendaftaran->created_at);
        $waktuSekarang = Carbon::now();
        $poliClean = strtolower($pendaftaran->poli);
        
        $namaDokter = $pendaftaran->nama_dokter ?? 'Dokter Tidak Diketahui';
        $dokterId = $pendaftaran->dokter_id ?? null;
        
        $jamMulaiPraktek = '07:00';
        $jamSelesaiPraktek = '11:30'; 

        if (str_contains($poliClean, 'gigi') || str_contains(strtolower($namaDokter), 'affrida') || $dokterId == 3) {
            $namaDokter = 'drg. Affrida Wahyu K.D';
            $jamMulaiPraktek = '08:00';
            $jamSelesaiPraktek = '12:00';
        } elseif (str_contains($poliClean, 'kia') || str_contains($poliClean, 'kb')) {
            if (str_contains(strtolower($namaDokter), 'dita') || $dokterId == 4) {
                $namaDokter = 'Dita Sevi A, S.Tr. Keb';
                $jamMulaiPraktek = '07:00';
                $jamSelesaiPraktek = '11:30';
            } else {
                $namaDokter = 'Nailis A, S.Tr. Keb., Bdn';
                $jamMulaiPraktek = '11:30';
                $jamSelesaiPraktek = '15:30';
            }
        } else {
            if (str_contains(strtolower($namaDokter), 'ferry') || $dokterId == 28 || $dokterId == 2) {
                $namaDokter = 'dr. Ferry Eko Santoso';
                $jamMulaiPraktek = '11:30';
                $jamSelesaiPraktek = '15:30';
            } else {
                $namaDokter = 'dr. Ahmad Syaikudin';
                $jamMulaiPraktek = '07:00';
                $jamSelesaiPraktek = '11:30';
            }
        }

        $antrianDiDepan = PendaftaranPoli::whereDate('created_at', $waktuDaftar->toDateString())
            ->whereIn('status', ['menunggu', 'menunggu_petugas'])
            ->where('id', '<', $pendaftaran->id)
            ->where(function($query) use ($namaDokter, $dokterId) {
                $query->where('nama_dokter', $namaDokter)
                      ->orWhere('dokter_id', $dokterId);
            })
            ->count();

        $sedangDiPeriksa = PendaftaranPoli::whereDate('created_at', $waktuDaftar->toDateString())
            ->where('status', 'diproses_dokter')
            ->where(function($query) use ($namaDokter, $dokterId) {
                $query->where('nama_dokter', $namaDokter)
                      ->orWhere('dokter_id', $dokterId);
            })
            ->exists();

        if ($pendaftaran->status === 'diproses_dokter') {
            $prediksiWaktu = "Silahkan Masuk Ruangan";
        } else {
            $tambahanWaktuSesi = $sedangDiPeriksa ? 15 : 0;
            $totalMenitTunggu = ($antrianDiDepan * 15) + $tambahanWaktuSesi;

            $waktuMulaiTarget = Carbon::parse($pendaftaran->created_at)->setTimeFromTimeString($jamMulaiPraktek);
            $waktuSelesaiTarget = Carbon::parse($pendaftaran->created_at)->setTimeFromTimeString($jamSelesaiPraktek);

            if ($waktuSekarang->lt($waktuMulaiTarget)) {
                $waktuEstimasiPanggil = $waktuMulaiTarget->addMinutes($totalMenitTunggu);
                $prediksiWaktu = '± ' . $waktuEstimasiPanggil->format('H:i') . ' WIB';
            } elseif ($waktuSekarang->gt($waktuSelesaiTarget) && $waktuDaftar->isToday()) {
                $prediksiWaktu = "Sesi Praktik Selesai";
            } else {
                $waktuEstimasiPanggil = $waktuSekarang->addMinutes($totalMenitTunggu);
                if ($totalMenitTunggu === 0) {
                    $prediksiWaktu = "Silahkan Menuju Ruang Poli";
                } else {
                    $prediksiWaktu = '± ' . $waktuEstimasiPanggil->format('H:i') . ' WIB';
                }
            }
        }

        return view('pasien.antrian', [
            'data' => $pendaftaran,
            'antrianDiDepan' => $antrianDiDepan,
            'prediksi' => $prediksiWaktu
        ]);
    }
}