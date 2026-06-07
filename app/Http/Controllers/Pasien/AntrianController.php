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

        // 2. LOGIKA PENENTUAN SESI BERDASARKAN DOKTER YANG TERDAFTAR DI DATABASE PASIEN
        $waktuDaftar = Carbon::parse($pendaftaran->created_at);
        $waktuSekarang = Carbon::now();
        $jamSekarang = $waktuSekarang->format('H:i');
        $poliClean = strtolower($pendaftaran->poli);
        
        // Ambil data dokter langsung dari record database pendaftaran
        $namaDokter = $pendaftaran->nama_dokter ?? 'Dokter Tidak Diketahui';
        
        // Atur default jam praktek operasional
        $jamMulaiPraktek = '07:00';
        $jamSelesaiPraktek = '11:30'; 

        // Sesuaikan jam operasional berdasarkan string nama dokter asli dari DB
        if (str_contains(strtolower($namaDokter), 'affrida') || str_contains($poliClean, 'gigi')) {
            $namaDokter = 'drg. Affrida Wahyu K.D';
            $jamMulaiPraktek = '08:00';
            $jamSelesaiPraktek = '12:00';
        } elseif (str_contains(strtolower($namaDokter), 'dita')) {
            $namaDokter = 'Dita Sevi A, S.Tr. Keb';
            $jamMulaiPraktek = '07:00';
            $jamSelesaiPraktek = '11:30';
        } elseif (str_contains(strtolower($namaDokter), 'nailis')) {
            $namaDokter = 'Nailis A, S.Tr. Keb., Bdn';
            $jamMulaiPraktek = '11:30';
            $jamSelesaiPraktek = '15:30';
        } elseif (str_contains(strtolower($namaDokter), 'ferry')) {
            $namaDokter = 'dr. Ferry Eko Santoso';
            $jamMulaiPraktek = '11:30';
            $jamSelesaiPraktek = '15:30';
        } else {
            // Default jika pasien memilih Ahmad Syaikudin atau belum terpetakan
            $namaDokter = 'dr. Ahmad Syaikudin';
            $jamMulaiPraktek = '07:00';
            $jamSelesaiPraktek = '11:30';
        }

        // 3. HITUNG SISA ANTREAN DI DEPAN (PER DOKTER PILIHAN YANG SINKRON)
        $antrianDiDepan = PendaftaranPoli::whereDate('created_at', $waktuDaftar->toDateString())
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
        $sedangDiPeriksa = PendaftaranPoli::whereDate('created_at', $waktuDaftar->toDateString())
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

            $waktuMulaiTarget = Carbon::parse($pendaftaran->created_at)->setTimeFromTimeString($jamMulaiPraktek);
            $waktuSelesaiTarget = Carbon::parse($pendaftaran->created_at)->setTimeFromTimeString($jamSelesaiPraktek);

            if ($waktuSekarang->lt($waktuMulaiTarget)) {
                // Kasus 1: Mengakses tiket sebelum jam praktik dokter tersebut dimulai
                $waktuEstimasiPanggil = $waktuMulaiTarget->addMinutes($totalMenitTunggu);
                $prediksiWaktu = '± ' . $waktuEstimasiPanggil->format('H:i') . ' WIB';
            } elseif ($waktuSekarang->gt($waktuSelesaiTarget) && $waktuDaftar->isToday()) {
                // Kasus 2: Jam praktik sesi dokter hari ini sudah berakhir
                $prediksiWaktu = "Sesi Praktik Selesai";
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