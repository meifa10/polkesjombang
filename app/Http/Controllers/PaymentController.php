<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use App\Models\Pembayaran;
use App\Models\PendaftaranPoli;
use App\Services\PaymentService;

class PaymentController extends Controller
{
    public function pay($id, PaymentService $paymentService)
    {
        if (!Auth::check()) {
            return redirect('/login');
        }

        $user = Auth::user();
        $pembayaran = Pembayaran::with('pendaftaran')
            ->where('id', $id)
            ->whereHas('pendaftaran', function ($q) use ($user) {
                $q->where('nama_pasien', $user->name);
            })->first();

        if (!$pembayaran) {
            return redirect('/dashboard')->with('error', 'Pembayaran tidak ditemukan');
        }

        if ($pembayaran->status == 'lunas') {
            return redirect('/dashboard')->with('success', 'Pembayaran sudah lunas');
        }

        // AMBIL DARI DATABASE (TIDAK LAGI MENGGUNAKAN MODEL MASTER TARIF)
        $tarifDokter = DB::table('pengaturans')->where('key', 'tarif_dokter')->value('value') ?? 10000;
        $tarifAdmin = DB::table('pengaturans')->where('key', 'tarif_admin')->value('value') ?? 10000;
        $totalFix = $tarifDokter + $tarifAdmin + ($pembayaran->total_obat ?? 0);

        try {
            // KIRIM TARIF TERBARU KE PAYMENT SERVICE
            $result = $paymentService->createTransaction($pembayaran, $tarifDokter, $tarifAdmin, $totalFix);
            session(['last_order_id' => $pembayaran->payment_ref]);

            return view('payment.pay', [
                'snapToken' => $result['snap_token'],
                'pembayaran' => $pembayaran,
                'tarifDokter' => $tarifDokter,
                'tarifAdmin' => $tarifAdmin,
                'totalFix' => $totalFix
            ]);
        } catch (\Exception $e) {
            Log::error('Midtrans Error: ' . $e->getMessage());
            return redirect('/dashboard')->with('error', 'Gagal memproses pembayaran');
        }
    }

    public function cetakStruk($id)
    {
        $pembayaran = Pembayaran::with(['pendaftaran.rekamMedis'])->findOrFail($id);

        if ($pembayaran->status != 'lunas') {
            abort(403, 'Struk hanya dapat dicetak untuk pembayaran yang sudah lunas.');
        }

        // AMBIL DARI DATABASE (TIDAK LAGI MENGGUNAKAN MODEL MASTER TARIF)
        $biayaDokter = DB::table('pengaturans')->where('key', 'tarif_dokter')->value('value') ?? $pembayaran->biaya_dokter;
        $biayaAdmin = DB::table('pengaturans')->where('key', 'tarif_admin')->value('value') ?? $pembayaran->biaya_admin;

        $resepString = $pembayaran->pendaftaran->rekamMedis->resep ?? '';
        $totalHargaObat = (int) str_replace(['.', ','], '', $pembayaran->total_obat ?? 0);
        $rincianObat = $this->parseResepPecahDetail($resepString, $totalHargaObat);

        return view('payment.struk', compact('pembayaran', 'rincianObat', 'biayaDokter', 'biayaAdmin'));
    }

    private function parseResepPecahDetail($resepString, $totalHargaObat = 0)
    {
        $listObat = [];
        if (empty(trim($resepString))) return $listObat;

        $rows = preg_split('/[\n,]+/', $resepString);
        $barisValid = [];
        foreach ($rows as $row) {
            $row = trim($row);
            if (!empty($row)) $barisValid[] = $row;
        }

        $jumlahBaris = count($barisValid);
        foreach ($barisValid as $row) {
            $listObat[] = [
                'nama'  => $row,
                'qty'   => 1,
                'harga' => $totalHargaObat > 0 ? (int)($totalHargaObat / $jumlahBaris) : 0,
                'total' => $totalHargaObat > 0 ? (int)($totalHargaObat / $jumlahBaris) : 0
            ];
        }
        return $listObat;
    }

    public function callback(Request $request) { /* kode callback Anda */ }
    public function finish(Request $request) { /* kode finish Anda */ }
}