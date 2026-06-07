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

        // 2. LOGIKA PENENTUAN SESI DOKTER
        $waktuDaftar = Carbon::parse($pendaftaran->created_at);
        $jamMenitDaftar = $waktuDaftar->format('H:i');
        $waktuSekarang = Carbon::now();
        $jamSekarang = $waktuSekarang->format('H:i');
        $poliClean = strtolower($pendaftaran->poli);
        
        // Ambil data dari kolom dokter (ubah ke huruf kecil semua agar mudah dicocokkan)
        // SILAKAN GANTI 'nama_dokter' DI BAWAH JIKA NAMA KOLOM DI DATABASE ANDA BERBEDA
        $dokterTerpilih = $pendaftaran->nama_dokter ? strtolower($pendaftaran->nama_dokter) : '';
        
        $namaDokter = 'Dokter Tidak Diketahui';
        $jamMulaiPraktek = '07:00';
        $jamSelesaiPraktek = '11:30'; 

        if (str_contains($poliClean, 'gigi') || str_contains($dokterTerpilih, 'affrida')) {
            $namaDokter = 'drg. Affrida Wahyu K.D';
            $jamMulaiPraktek = '08:00';
            $jamSelesaiPraktek = '12:00';
        } elseif (str_contains($poliClean, 'kia') || str_contains($poliClean, 'kb')) {
            if (str_contains($dokterTerpilih, 'dita')) {
                $namaDokter = 'Dita Sevi A, S.Tr. Keb';
                $jamMulaiPraktek = '07:00';
                $jamSelesaiPraktek = '11:30';
            } else {
                $namaDokter = 'Nailis A, S.Tr. Keb., Bdn';
                $jamMulaiPraktek = '11:30';
                $jamSelesaiPraktek = '15:30';
            }
        } else {
            // Skenario Poli Umum
            // Kunci Utama: Jika data di database mengandung kata 'ferry' ATAU ID dokter sesi siang
            if (str_contains($dokterTerpilih, 'ferry') || $dokterTerpilih == '2') { 
                $namaDokter = 'dr. Ferry Eko Santoso';
                $jamMulaiPraktek = '11:30';
                $jamSelesaiPraktek = '15:30';
            } else {
                // Default jika memilih ahmad atau jika kolom dokter di database ternyata KOSONG (null)
                $namaDokter = 'dr. Ahmad Syaikudin';
                $jamMulaiPraktek = '07:00';
                $jamSelesaiPraktek = '11:30';
            }
        }

        // 3. HITUNG SISA ANTREAN DI DEPAN (BERDASARKAN DOKTER YANG SUDAH TERKUNCI)
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

        // 4. CEK APAKAH ADA PASIEN YANG SEDNG DI DALAM RUANGAN DOKTER
        $sedangDiPeriksa = PendaftaranPoli::whereDate('created_at', $waktuDaftar->toDateString())
            ->where('status', 'diproses_dokter')
            ->where(function($query) use ($namaDokter, $pendaftaran) {
                $query->where('nama_dokter', $namaDokter)
                      ->orWhere(function($q) use ($pendaftaran) {
                          $q->whereNull('nama_dokter')->where('poli', $pendaftaran->poli);
                      });
            })
            ->exists();

        // 5. KALKULASI ESTIMASI WAKTU TUNGGU
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