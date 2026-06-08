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

        try {
            $result = $paymentService->createTransaction($pembayaran);
            session(['last_order_id' => $pembayaran->payment_ref]);

            return view('payment.pay', [
                'snapToken' => $result['snap_token'],
                'pembayaran' => $pembayaran
            ]);
        } catch (\Exception $e) {
            Log::error('Midtrans Error: ' . $e->getMessage());
            return redirect('/dashboard')->with('error', 'Gagal memproses pembayaran');
        }
    }

    public function callback(Request $request)
    {
        $orderId = $request->order_id;
        $transactionStatus = $request->transaction_status;
        $paymentType = $request->payment_type ?? 'midtrans';

        $pembayaran = Pembayaran::where('payment_ref', $orderId)->first();

        if (!$pembayaran) {
            return response()->json(['message' => 'Not Found'], 404);
        }

        DB::beginTransaction();
        try {
            if (in_array($transactionStatus, ['capture', 'settlement'])) {
                $pembayaran->update([
                    'status' => 'lunas',
                    'paid_by' => $paymentType,
                    'tanggal_bayar' => now(),
                    'metode' => 'midtrans'
                ]);

                PendaftaranPoli::where('id', $pembayaran->pendaftaran_id)
                    ->update(['status' => 'selesai']);

                Log::info("Payment Success for Order ID: $orderId");
            } elseif ($transactionStatus == 'pending') {
                $pembayaran->update(['status' => 'pending']);
            } elseif (in_array($transactionStatus, ['deny', 'cancel', 'expire'])) {
                $pembayaran->update(['status' => 'gagal']);
            }

            DB::commit();
            return response()->json(['message' => 'OK']);
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Callback Error: ' . $e->getMessage());
            return response()->json(['message' => 'ERROR'], 500);
        }
    }

    public function finish(Request $request)
    {
        $orderId = $request->order_id ?? $request->query('order_id') ?? session('last_order_id');

        if (!$orderId) {
            return redirect('/dashboard')->with('error', 'Order ID tidak ditemukan');
        }

        $pembayaran = Pembayaran::where('payment_ref', $orderId)->first();

        if ($pembayaran) {
            DB::transaction(function () use ($pembayaran) {
                $pembayaran->update([
                    'status' => 'lunas',
                    'paid_by' => 'midtrans',
                    'tanggal_bayar' => now(),
                    'metode' => 'midtrans'
                ]);

                PendaftaranPoli::where('id', $pembayaran->pendaftaran_id)
                    ->update(['status' => 'selesai']);
            });

            return redirect('/dashboard')->with('success', 'Pembayaran berhasil dikonfirmasi');
        }

        return redirect('/dashboard');
    }

    public function cetakStruk($id)
    {
        $pembayaran = Pembayaran::with(['pendaftaran.rekamMedis'])->findOrFail($id);

        if ($pembayaran->status != 'lunas') {
            abort(403, 'Struk hanya dapat dicetak untuk pembayaran yang sudah lunas.');
        }

        $biayaDokter = MasterTarif::where('nama_layanan', 'Jasa Dokter')->value('harga') ?? $pembayaran->biaya_dokter;
        $biayaAdmin = MasterTarif::where('nama_layanan', 'Administrasi')->value('harga') ?? $pembayaran->biaya_admin;

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
            if (!empty($row)) {
                $barisValid[] = $row;
            }
        }

        $jumlahBaris = count($barisValid);
        if ($jumlahBaris === 0) return $listObat;

        foreach ($barisValid as $row) {
            $namaObat = $row;
            $qty = 1;
            $hargaSatuan = 0;

            if (str_contains($row, 'x') && str_contains($row, '@')) {
                $partHarga = explode('@', $row);
                $hargaSatuan = isset($partHarga[1]) ? (int)preg_replace('/[^0-9]/', '', $partHarga[1]) : 0;
                $partNamaQty = explode('x', $partHarga[0]);
                $namaObat = isset($partNamaQty[0]) ? trim($partNamaQty[0]) : trim($partHarga[0]);
                $qty = isset($partNamaQty[1]) ? (int)preg_replace('/[^0-9]/', '', $partNamaQty[1]) : 1;
            } elseif (preg_match('/^(.*?)\s*\(((\d+)\s*[pP][cC][sS]|\d+)\)/', $row, $matches)) {
                $namaObat = trim($matches[1]);
                $qty = (int)$matches[3];
                if ($totalHargaObat > 0 && $qty > 0) {
                    $hargaSatuan = (int)($totalHargaObat / $jumlahBaris / $qty);
                }
            } elseif (str_contains($row, '-')) {
                $partStrip = explode('-', $row);
                $namaObat = trim($partStrip[0]);
                if (preg_match('/\((\d+)\s*\w+\)/', $partStrip[0], $qtyMatches)) {
                    $qty = (int)$qtyMatches[1];
                }
                if ($totalHargaObat > 0 && $qty > 0) {
                    $hargaSatuan = (int)($totalHargaObat / $jumlahBaris / $qty);
                }
            } elseif (str_contains($row, 'x') || str_contains($row, 'X')) {
                $delimiter = str_contains($row, 'x') ? 'x' : 'X';
                $partNamaQty = explode($delimiter, $row);
                $namaObat = trim($partNamaQty[0]);
                $qty = isset($partNamaQty[1]) ? (int)preg_replace('/[^0-9]/', '', $partNamaQty[1]) : 1;
                if ($totalHargaObat > 0 && $qty > 0) {
                    $hargaSatuan = (int)($totalHargaObat / $jumlahBaris / $qty);
                }
            } else {
                if ($totalHargaObat > 0) {
                    $hargaSatuan = (int)($totalHargaObat / $jumlahBaris);
                }
            }

            if (str_contains($namaObat, '(')) {
                $partClean = explode('(', $namaObat);
                $namaObat = trim($partClean[0]);
            }

            $listObat[] = [
                'nama'  => rtrim($namaObat, ' -:'),
                'qty'   => $qty,
                'harga' => $hargaSatuan,
                'total' => $qty * $hargaSatuan
            ];
        }

        return $listObat;
    }
}