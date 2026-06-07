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

        // 2. LOGIKA PENENTUAN DOKTER BERDASARKAN WAKTU PENDAFTARAN PASIEN
        $waktuDaftar = Carbon::parse($pendaftaran->created_at);
        $jamMenitDaftar = $waktuDaftar->format('H:i');
        $poliClean = strtolower($pendaftaran->poli);
        
        $namaDokter = 'Dokter Tidak Diketahui';
        $jamMulaiPraktek = '07:00'; // Default jam buka kliring standar

        if (str_contains($poliClean, 'gigi')) {
            $namaDokter = 'drg. Affrida Wahyu K.D';
            $jamMulaiPraktek = '08:00';
        } elseif (str_contains($poliClean, 'kia') || str_contains($poliClean, 'kb')) {
            if ($jamMenitDaftar < '11:30') {
                $namaDokter = 'Dita Sevi A, S.Tr. Keb';
                $jamMulaiPraktek = '07:00';
            } else {
                $namaDokter = 'Nailis A, S.Tr. Keb., Bdn';
                $jamMulaiPraktek = '11:30';
            }
        } else {
            // Default Poli Umum
            if ($jamMenitDaftar < '11:30') {
                $namaDokter = 'dr. Ahmad Syaikudin';
                $jamMulaiPraktek = '07:00';
            } else {
                $namaDokter = 'dr. Ferry Eko Santoso';
                $jamMulaiPraktek = '11:30';
            }
        }

        // 3. HITUNG SISA ANTREAN DI DEPAN (DIHITUNG KHUSUS PER DOKTER AKTIF)
        $antrianDiDepan = PendaftaranPoli::whereDate('created_at', Carbon::today())
            ->whereIn('status', ['menunggu', 'menunggu_petugas'])
            ->where('id', '<', $pendaftaran->id)
            ->where(function($query) use ($namaDokter, $pendaftaran) {
                // Mencari antrean yang dokternya sama, ATAU jika field nama_dokter di DB masih kosong, filter via kecocokan Poli
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

        // 5. KALKULASI ESTIMASI WAKTU TUNGGU SECARA REAL-TIME
        if ($pendaftaran->status === 'diproses_dokter') {
            $prediksiWaktu = "Silahkan Masuk Ruangan";
        } else {
            $tambahanWaktuSesi = $sedangDiPeriksa ? 15 : 0;
            $totalMenitTunggu = ($antrianDiDepan * 15) + $tambahanWaktuSesi;

            // Logika Tambahan: Jika pasien mendaftar di waktu sekarang (Now) tapi jam praktik dokter belum mulai,
            // Basis perhitungan estimasi jam panggil dimulai dari jam buka praktik dokter tersebut, bukan jam sekarang.
            $waktuSekarang = Carbon::now();
            $waktuTargetPraktek = Carbon::today()->setTimeFromTimeString($jamMulaiPraktek);

            if ($waktuSekarang->lt($waktuTargetPraktek)) {
                // Dokter belum masuk/mulai praktek, hitung dari target jam mulai dokter + akumulasi antrean
                $waktuEstimasiPanggil = $waktuTargetPraktek->addMinutes($totalMenitTunggu);
            } else {
                // Dokter sudah mulai praktek, hitung langsung dari menit waktu sekarang
                $waktuEstimasiPanggil = $waktuSekarang->addMinutes($totalMenitTunggu);
            }

            if ($totalMenitTunggu === 0 && $waktuSekarang->gte($waktuTargetPraktek)) {
                $prediksiWaktu = "Silahkan Menuju Ruang Poli";
            } else {
                $prediksiWaktu = '± ' . $waktuEstimasiPanggil->format('H:i') . ' WIB';
            }
        }

        return view('pasien.antrian', [
            'data' => $pendaftaran,
            'antrianDiDepan' => $antrianDiDepan,
            'prediksi' => $prediksiWaktu
        ]);
    }
}