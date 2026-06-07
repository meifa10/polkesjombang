<?php

namespace App\Http\Controllers\Pasien;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use App\Models\PendaftaranPoli;
use Carbon\Carbon;
use Illuminate\Http\Request;

class AntrianController extends Controller
{
    public function index(Request $request)
    {
        $user = Auth::user();
        
        // Ambil parameter poli dari URL (contoh: ?poli=Poli Umum)
        $targetPoli = $request->query('poli');

        // 1. Cari pendaftaran aktif pasien
        $query = PendaftaranPoli::where('nama_pasien', $user->name)
            ->whereIn('status', ['menunggu', 'menunggu_petugas', 'diproses_dokter']);
            
        // Jika pasien memilih poli spesifik dari menu dashboard, saring berdasarkan poli tersebut
        if ($targetPoli) {
            $query->where('poli', $targetPoli);
        }

        $pendaftaran = $query->latest()->first();

        // Jika tidak ada antrean sama sekali, kembalikan ke dashboard
        if (!$pendaftaran) {
            return redirect()->route('dashboard')->with('error', 'Antrian aktif pada layanan ini tidak ditemukan.');
        }

        // 2. LOGIKA PENENTUAN SESI JADWAL DOKTER BERDASARKAN TIKET YANG DI-KLIK
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

        // 3. HITUNG SISA ANTREAN DI DEPAN KHUSUS DOKTER INI
        $antrianDiDepan = PendaftaranPoli::whereDate('created_at', $waktuDaftar->toDateString())
            ->whereIn('status', ['menunggu', 'menunggu_petugas'])
            ->where('id', '<', $pendaftaran->id)
            ->where(function($qQuery) use ($namaDokter, $pendaftaran) {
                $qQuery->where('nama_dokter', $namaDokter)
                      ->orWhere(function($subQ) use ($pendaftaran) {
                          $subQ->whereNull('nama_dokter')->where('poli', $pendaftaran->poli);
                      });
            })
            ->count();

        // 4. CEK PASIEN DI DALAM RUANGAN DOKTER TERSEBUT
        $sedangDiPeriksa = PendaftaranPoli::whereDate('created_at', $waktuDaftar->toDateString())
            ->where('status', 'diproses_dokter')
            ->where(function($qQuery) use ($namaDokter, $pendaftaran) {
                $qQuery->where('nama_dokter', $namaDokter)
                      ->orWhere(function($subQ) use ($pendaftaran) {
                          $subQ->whereNull('nama_dokter')->where('poli', $pendaftaran->poli);
                      });
            })
            ->exists();

        // 5. KALKULASI ESTIMASI WAKTU TUNGGU REAL-TIME
        if ($pendaftaran->status === 'diproses_dokter') {
            $prediksiWaktu = "Silahkan Masuk Ruangan";
        } else {
            $tambahanWaktuSesi = $sedangDiPeriksa ? 15 : 0;
            $totalMenitTunggu = ($antrianDiDepan * 15) + $tambahanWaktuSesi;

            $waktuSekarang = Carbon::now();
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

        // Lempar 1 data bersih tunggal ke Blade tanpa perulangan array lagi
        return view('pasien.antrian', [
            'data' => $pendaftaran,
            'antrianDiDepan' => $antrianDiDepan,
            'prediksi' => $prediksiWaktu,
            'namaDokter' => $namaDokter,
            'jamPraktek' => $jamPraktek,
            'catatanEdukasi' => $catatanEdukasi ?? null
        ]);
    }
}