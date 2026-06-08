<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use App\Models\Pembayaran;
use App\Models\PendaftaranPoli;
use App\Models\MasterTarif;
use App\Services\PaymentService;

class PaymentController extends Controller
{
    public function pay($id, PaymentService $paymentService)
    {
        if (!Auth::check()) return redirect('/login');

        $user = Auth::user();
        $pembayaran = Pembayaran::with('pendaftaran')
            ->where('id', $id)
            ->whereHas('pendaftaran', function ($q) use ($user) {
                $q->where('nama_pasien', $user->name);
            })->first();

        if (!$pembayaran || $pembayaran->status == 'lunas') {
            return redirect('/dashboard')->with('error', 'Pembayaran tidak ditemukan atau sudah lunas.');
        }

        // Ambil tarif terbaru dari database secara real-time
        $tarifDokter = MasterTarif::where('nama_layanan', 'Jasa Dokter')->value('harga') ?? 10000;
        $tarifAdmin = MasterTarif::where('nama_layanan', 'Administrasi')->value('harga') ?? 10000;
        $totalFix = $tarifDokter + $tarifAdmin + ($pembayaran->total_obat ?? 0);

        try {
            // Kirim tarif ke service untuk sinkronisasi nominal Midtrans
            $result = $paymentService->createTransaction($pembayaran, $tarifDokter, $tarifAdmin, $totalFix);
            session(['last_order_id' => $pembayaran->payment_ref]);

            return view('payment.pay', compact('pembayaran', 'tarifDokter', 'tarifAdmin', 'totalFix', 'result'));
        } catch (\Exception $e) {
            Log::error('Midtrans Error: ' . $e->getMessage());
            return redirect('/dashboard')->with('error', 'Gagal memproses pembayaran');
        }
    }

    public function cetakStruk($id)
    {
        $pembayaran = Pembayaran::with(['pendaftaran.rekamMedis'])->findOrFail($id);
        if ($pembayaran->status != 'lunas') abort(403, 'Akses ditolak.');

        $biayaDokter = MasterTarif::where('nama_layanan', 'Jasa Dokter')->value('harga') ?? $pembayaran->biaya_dokter;
        $biayaAdmin = MasterTarif::where('nama_layanan', 'Administrasi')->value('harga') ?? $pembayaran->biaya_admin;

        $rincianObat = $this->parseResepPecahDetail($pembayaran->pendaftaran->rekamMedis->resep ?? '', (int)($pembayaran->total_obat ?? 0));
        
        return view('payment.struk', compact('pembayaran', 'rincianObat', 'biayaDokter', 'biayaAdmin'));
    }

    private function parseResepPecahDetail($resepString, $totalHargaObat)
    {
        $listObat = [];
        if (empty(trim($resepString))) return $listObat;
        $rows = preg_split('/[\n,]+/', $resepString);
        foreach ($rows as $row) {
            if (!empty(trim($row))) {
                $listObat[] = ['nama' => trim($row), 'qty' => 1, 'harga' => $totalHargaObat/count($rows), 'total' => $totalHargaObat/count($rows)];
            }
        }
        return $listObat;
    }
}