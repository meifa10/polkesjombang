<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use App\Models\Pembayaran;
use App\Models\PendaftaranPoli;
use App\Models\Pengaturan; // Gunakan model Pengaturan
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

        // AMBIL TARIF DARI MODEL PENGATURAN
        $tarifDokter = Pengaturan::where('key', 'tarif_dokter')->value('value') ?? 10000;
        $tarifAdmin = Pengaturan::where('key', 'tarif_admin')->value('value') ?? 10000;
        $totalFix = $tarifDokter + $tarifAdmin + ($pembayaran->total_obat ?? 0);

        try {
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
        if ($pembayaran->status != 'lunas') abort(403);

        // AMBIL TARIF DARI MODEL PENGATURAN
        $biayaDokter = Pengaturan::where('key', 'tarif_dokter')->value('value') ?? $pembayaran->biaya_dokter;
        $biayaAdmin = Pengaturan::where('key', 'tarif_admin')->value('value') ?? $pembayaran->biaya_admin;

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

    // Callback dan Finish tetap sama ...
    public function callback(Request $request) { /* ... */ }
    public function finish(Request $request) { /* ... */ }
}