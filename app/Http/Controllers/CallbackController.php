<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Pembayaran;
use Midtrans\Config;
use Midtrans\Notification;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\DB;

class CallbackController extends Controller
{
    /**
     * =========================================
     * MIDTRANS CALLBACK / WEBHOOK
     * =========================================
     */
    public function handle(Request $request)
    {
        /**
         * =========================================
         * 1. CONFIG MIDTRANS (WAJIB)
         * =========================================
         */
        Config::$serverKey    = config('services.midtrans.server_key');
        Config::$isProduction = config('services.midtrans.is_production', false);
        Config::$isSanitized  = true;
        Config::$is3ds        = true;

        /**
         * =========================================
         * 2. AMBIL NOTIFICATION DARI MIDTRANS
         * =========================================
         */
        try {
            $notif = new Notification();
        } catch (\Exception $e) {
            Log::error('MIDTRANS INVALID PAYLOAD: ' . $e->getMessage());
            return response()->json(['message' => 'Invalid payload'], 400);
        }

        /**
         * =========================================
         * 3. AMBIL DATA PENTING
         * =========================================
         */
        $transactionStatus = $notif->transaction_status;
        $orderId           = $notif->order_id;
        $paymentType       = $notif->payment_type;
        $fraudStatus       = $notif->fraud_status ?? null;
        $grossAmount       = $notif->gross_amount;
        $signatureKey      = $notif->signature_key;

        Log::info('MIDTRANS CALLBACK MASUK', [
            'order_id' => $orderId,
            'status'   => $transactionStatus,
        ]);

        /**
         * =========================================
         * 4. VALIDASI SIGNATURE (PENTING BANGET)
         * =========================================
         */
        $serverKey = config('services.midtrans.server_key');

        $expectedSignature = hash(
            'sha512',
            $orderId . $notif->status_code . $grossAmount . $serverKey
        );

        if ($signatureKey !== $expectedSignature) {
            Log::warning("MIDTRANS SIGNATURE INVALID: $orderId");
            return response()->json(['message' => 'Invalid signature'], 403);
        }

        /**
         * =========================================
         * 5. CARI DATA PEMBAYARAN
         * =========================================
         */
        $pembayaran = Pembayaran::where('payment_ref', $orderId)->first();

        if (!$pembayaran) {
            Log::warning("MIDTRANS: TRANSAKSI TIDAK DITEMUKAN: $orderId");
            return response()->json(['message' => 'Not found'], 404);
        }

        /**
         * =========================================
         * 6. UPDATE STATUS (TRANSACTION SAFE)
         * =========================================
         */
        DB::beginTransaction();

        try {

            switch ($transactionStatus) {

                case 'capture':
                    if ($fraudStatus == 'challenge') {
                        $pembayaran->status = 'pending';
                    } else if ($fraudStatus == 'accept') {
                        $pembayaran->status = 'lunas';
                        $pembayaran->tanggal_bayar = now();
                    }
                    break;

                case 'settlement':
                    $pembayaran->status = 'lunas';
                    $pembayaran->tanggal_bayar = now();
                    break;

                case 'pending':
                    $pembayaran->status = 'belum_lunas';
                    break;

                case 'deny':
                case 'expire':
                case 'cancel':
                    $pembayaran->status = 'gagal';
                    break;

                default:
                    Log::warning("MIDTRANS STATUS TIDAK DIKENAL: $transactionStatus");
                    break;
            }

            /**
             * Simpan metode pembayaran
             */
            $pembayaran->paid_by = $paymentType;

            $pembayaran->save();

            DB::commit();

            Log::info("MIDTRANS SUCCESS: $orderId -> " . $pembayaran->status);

            return response()->json([
                'message' => 'OK'
            ], 200);

        } catch (\Exception $e) {

            DB::rollBack();

            Log::error("MIDTRANS DB ERROR: " . $e->getMessage());

            return response()->json([
                'message' => 'Server error'
            ], 500);
        }
    }
}