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

        // 1. AMBIL SEMUA PENDAFTARAN AKTIF (DILEPAS DARI HARI INI AGAR DATA TIDAK REJECT/KEMBALI KE DASHBOARD)
        $daftarPendaftaran = PendaftaranPoli::where('nama_pasien', $user->name)
            ->whereIn('status', ['menunggu', 'menunggu_petugas', 'diproses_dokter'])
            ->latest()
            ->get();

        // Jika benar-benar tidak ada pendaftaran yang berstatus aktif, baru kembalikan ke dashboard
        if ($daftarPendaftaran->isEmpty()) {
            return redirect()->route('dashboard')->with('error', 'Anda tidak memiliki antrian aktif saat ini.');
        }

        $antrianData = [];

        // 2. Loop semua paket antrean aktif untuk menghitung sisa orang di depan per dokter sesi
        foreach ($daftarPendaftaran as $pendaftaran) {
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

            // Hitung sisa antrean di depan khusus pada hari pendaftaran tersebut dibuat ($waktuDaftar)
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

            // Cek pasien dalam ruangan pada hari pendaftaran bersangkutan
            $sedangDiPeriksa = PendaftaranPoli::whereDate('created_at', $waktuDaftar->toDateString())
                ->where('status', 'diproses_dokter')
                ->where(function($query) use ($namaDokter, $pendaftaran) {
                    $query->where('nama_dokter', $namaDokter)
                          ->orWhere(function($q) use ($pendaftaran) {
                              $q->whereNull('nama_dokter')->where('poli', $pendaftaran->poli);
                          });
                })
                ->exists();

            // Logika Estimasi Waktu Kerja Real-time
            if ($pendaftaran->status === 'diproses_dokter') {
                $prediksiWaktu = "Silahkan Masuk Ruangan";
            } else {
                $tambahanWaktuSesi = $sedangDiPeriksa ? 15 : 0;
                $totalMenitTunggu = ($antrianDiDepan * 15) + $tambahanWaktuSesi;

                $waktuSekarang = Carbon::now();
                
                // Sinkronkan target jam operasional berdasarkan tanggal tiket dibuat
                $waktuMulaiTarget = Carbon::parse($pendaftaran->created_at)->setTimeFromTimeString($jamMulaiPraktek);
                $waktuSelesaiTarget = Carbon::parse($pendaftaran->created_at)->setTimeFromTimeString($jamSelesaiPraktek);

                if ($waktuSekarang->lt($waktuMulaiTarget)) {
                    $waktuEstimasiPanggil = $waktuMulaiTarget->addMinutes($totalMenitTunggu);
                    $prediksiWaktu = '± ' . $waktuEstimasiPanggil->format('H:i') . ' WIB';
                } elseif ($waktuSekarang->gt($waktuSelesaiTarget) && $waktuDaftar->isToday()) {
                    $prediksiWaktu = "Besok jam " . $jamMulaiPraktek . " WIB (Poli Tutup)";
                } else {
                    $waktuEstimasiPanggil = $waktuSekarang->addMinutes($totalMenitTunggu);
                    $prediksiWaktu = ($totalMenitTunggu === 0) ? "Silahkan Menuju Ruang Poli" : '± ' . $waktuEstimasiPanggil->format('H:i') . ' WIB';
                }
            }

            // Masukkan data paket antrean ke dalam array hasil
            $antrianData[] = [
                'pendaftaran' => $pendaftaran,
                'antrianDiDepan' => $antrianDiDepan,
                'prediksi' => $prediksiWaktu
            ];
        }

        return view('pasien.antrian', [
            'daftarAntrian' => $antrianData
        ]);
    }
}