<?php

namespace App\Http\Controllers;

use App\Models\Pembayaran;
use App\Services\PaymentService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\DB;

class PaymentController extends Controller
{
    /**
     * =========================
     * HALAMAN BAYAR
     * =========================
     */
    public function pay($id, PaymentService $paymentService)
    {
        // 🔥 HANDLE kalau diakses tanpa login (redirect dari Midtrans)
        if (!Auth::check()) {
            return redirect('/dashboard');
        }

        $user = Auth::user();

        // 🔥 gunakan first() bukan firstOrFail biar tidak 404
        $pembayaran = Pembayaran::where('id', $id)
            ->where('status', 'belum_lunas')
            ->whereHas('pendaftaran', function ($q) use ($user) {
                $q->where('nama_pasien', $user->name);
            })
            ->first();

        // 🔥 kalau tidak ditemukan (biasanya dari redirect Midtrans)
        if (!$pembayaran) {
            return redirect('/dashboard');
        }

        try {
            $result = $paymentService->createTransaction($pembayaran);

            return view('payment.pay', [
                'snapToken' => $result['snap_token'],
                'pembayaran' => $pembayaran
            ]);
        } catch (\Exception $e) {
            Log::error('Midtrans Error: ' . $e->getMessage());
            return redirect('/dashboard')->with('error', 'Gagal memproses pembayaran.');
        }
    }

    /**
     * =========================
     * CALLBACK MIDTRANS
     * =========================
     */
    public function callback(Request $request)
    {
        Log::info('CALLBACK MASUK', $request->all());

        $data = $request->all();

        $orderId      = $data['order_id'] ?? null;
        $statusCode   = $data['status_code'] ?? null;
        $grossAmount  = $data['gross_amount'] ?? null;
        $signatureKey = $data['signature_key'] ?? null;
        $serverKey    = config('services.midtrans.server_key');

        // VALIDASI SIGNATURE
        $hashed = hash("sha512", $orderId . $statusCode . $grossAmount . $serverKey);

        if ($hashed !== $signatureKey) {
            Log::warning("Signature tidak valid: " . $orderId);
            return response()->json(['message' => 'Invalid signature'], 403);
        }

        // CARI DATA PEMBAYARAN
        $pembayaran = Pembayaran::where('payment_ref', $orderId)->first();

        if (!$pembayaran) {
            Log::error("Pembayaran tidak ditemukan: " . $orderId);
            return response()->json(['message' => 'Not found'], 404);
        }

        $transactionStatus = $data['transaction_status'];
        $paymentType       = $data['payment_type'] ?? 'midtrans';

        try {
            DB::transaction(function () use ($pembayaran, $transactionStatus, $paymentType) {

                if (in_array($transactionStatus, ['settlement', 'capture'])) {

                    $pembayaran->update([
                        'status'        => 'lunas',
                        'tanggal_bayar' => now(),
                        'paid_by'       => $paymentType,
                        'metode'        => 'transfer',
                    ]);

                    Log::info("✅ LUNAS: " . $pembayaran->payment_ref);

                } elseif ($transactionStatus == 'pending') {

                    $pembayaran->update([
                        'status' => 'belum_lunas'
                    ]);

                } elseif (in_array($transactionStatus, ['deny', 'expire', 'cancel'])) {

                    $pembayaran->update([
                        'status' => 'gagal'
                    ]);
                }
            });

            return response()->json(['message' => 'OK']);

        } catch (\Exception $e) {
            Log::error("ERROR UPDATE: " . $e->getMessage());
            return response()->json(['message' => 'Error'], 500);
        }
    }

    /**
     * =========================
     * FINISH (SETELAH BAYAR)
     * =========================
     */
    public function finish(Request $request)
    {
        Log::info('FINISH MASUK', $request->all());

        return redirect('/dashboard')->with('success', 'Pembayaran berhasil');
    }

    /**
     * =========================
     * ERROR (GAGAL BAYAR)
     * =========================
     */
    public function error()
    {
        return redirect('/dashboard')->with('error', 'Pembayaran gagal');
    }
}