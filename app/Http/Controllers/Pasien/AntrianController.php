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

        // 1. Ambil pendaftaran aktif milik user pasien saat ini
        $pendaftaran = PendaftaranPoli::where('nama_pasien', $user->name)
            ->whereIn('status', ['menunggu', 'menunggu_petugas', 'diproses_dokter'])
            ->latest()
            ->first();

        if (!$pendaftaran) {
            return redirect()->route('dashboard')->with('error', 'Antrian aktif tidak ditemukan.');
        }

        // 2. LOGIKA PENENTUAN SESI JADWAL DOKTER BERDASARKAN WAKTU DAFTAR
        $waktuDaftar = Carbon::parse($pendaftaran->created_at);
        $jamMenitDaftar = $waktuDaftar->format('H:i');
        $poliClean = strtolower($pendaftaran->poli);
        
        $namaDokter = 'Dokter Tidak Diketahui';
        $jamMulaiPraktek = '07:00';
        $jamSelesaiPraktek = '11:30'; 

        if (str_contains($poliClean, 'gigi')) {
            $namaDokter = 'drg. Affrida Wahyu K.D';
            $jamMulaiPraktek = '08:00';
            $jamSelesaiPraktek = '12:00';
        } elseif (str_contains($poliClean, 'kia') || str_contains($poliClean, 'kb')) {
            if ($jamMenitDaftar < '11:30') {
                $namaDokter = 'Dita Sevi A, S.Tr. Keb';
                $jamMulaiPraktek = '07:00';
                $jamSelesaiPraktek = '11:30';
            } else {
                $namaDokter = 'Nailis A, S.Tr. Keb., Bdn';
                $jamMulaiPraktek = '11:30';
                $jamSelesaiPraktek = '15:30';
            }
        } else {
            // Default Poli Umum
            if ($jamMenitDaftar < '11:30') {
                $namaDokter = 'dr. Ahmad Syaikudin';
                $jamMulaiPraktek = '07:00';
                $jamSelesaiPraktek = '11:30';
            } else {
                $namaDokter = 'dr. Ferry Eko Santoso';
                $jamMulaiPraktek = '11:30';
                $jamSelesaiPraktek = '15:30';
            }
        }

        // 3. HITUNG SISA ANTREAN DI DEPAN (PER DOKTER AKTIF)
        $antrianDiDepan = PendaftaranPoli::whereDate('created_at', Carbon::today())
            ->whereIn('status', ['menunggu', 'menunggu_petugas'])
            ->where('id', '<', $pendaftaran->id)
            ->where(function($query) use ($namaDokter, $pendaftaran) {
                $query->where('nama_dokter', $namaDokter)
                      ->orWhere(function($q) use ($pendaftaran) {
                          $q->whereNull('nama_dokter')->where('poli', $pendaftaran->poli);
                      });
            })
            ->count();

        // 4. CEK APAKAH ADA PASIEN YANG SEDANG DI DALAM RUANGAN DOKTER TERSEBUT
        $sedangDiPeriksa = PendaftaranPoli::whereDate('created_at', Carbon::today())
            ->where('status', 'diproses_dokter')
            ->where(function($query) use ($namaDokter, $pendaftaran) {
                $query->where('nama_dokter', $namaDokter)
                      ->orWhere(function($q) use ($pendaftaran) {
                          $q->whereNull('nama_dokter')->where('poli', $pendaftaran->poli);
                      });
            })
            ->exists();

        // 5. KALKULASI ESTIMASI WAKTU TUNGGU SECARA REAL-TIME & ADAPTIF
        if ($pendaftaran->status === 'diproses_dokter') {
            $prediksiWaktu = "Silahkan Masuk Ruangan";
        } else {
            $tambahanWaktuSesi = $sedangDiPeriksa ? 15 : 0;
            $totalMenitTunggu = ($antrianDiDepan * 15) + $tambahanWaktuSesi;

            $waktuSekarang = Carbon::now();
            $waktuMulaiTarget = Carbon::today()->setTimeFromTimeString($jamMulaiPraktek);
            $waktuSelesaiTarget = Carbon::today()->setTimeFromTimeString($jamSelesaiPraktek);

            if ($waktuSekarang->lt($waktuMulaiTarget)) {
                // Kasus 1: Masih terlalu pagi (sebelum jam praktik dimulai)
                $waktuEstimasiPanggil = $waktuMulaiTarget->addMinutes($totalMenitTunggu);
                $prediksiWaktu = '± ' . $waktuEstimasiPanggil->format('H:i') . ' WIB';
            } elseif ($waktuSekarang->gt($waktuSelesaiTarget)) {
                // Kasus 2: Sudah melewati jam selesai praktik (kemalaman seperti jam 23.46 saat ini)
                $prediksiWaktu = "Besok jam " . $jamMulaiPraktek . " WIB (Poli Sudah Tutup)";
            } else {
                // Kasus 3: Berada di dalam rentang jam praktik dokter aktif
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