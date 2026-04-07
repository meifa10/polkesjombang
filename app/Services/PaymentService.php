<?php

namespace App\Services;

use Midtrans\Snap;
use Midtrans\Config;
use Illuminate\Support\Facades\Log;

class PaymentService
{
    public function __construct()
    {
        Config::$serverKey    = env('MIDTRANS_SERVER_KEY');
        Config::$isProduction = env('MIDTRANS_IS_PRODUCTION', false);
        Config::$isSanitized  = true;
        Config::$is3ds        = true;

        if (empty(Config::$serverKey)) {
            Log::error("❌ MIDTRANS ERROR: Server Key kosong!");
            throw new \Exception("Midtrans Server Key tidak ditemukan di .env");
        }
    }

    public function createTransaction($pembayaran)
    {
        /**
         * =========================
         * 1. VALIDASI DATA
         * =========================
         */
        if (!$pembayaran) {
            throw new \Exception("Data pembayaran tidak ditemukan");
        }

        if ((int)$pembayaran->total_biaya <= 0) {
            throw new \Exception("Total biaya tidak valid");
        }

        /**
         * =========================
         * 2. CEK ORDER ID (WAJIB STABIL)
         * =========================
         */
        $order_id = $pembayaran->payment_ref;

        if (empty($order_id)) {
            $order_id = 'PAY-' . $pembayaran->id . '-' . time();
        }

        /**
         * =========================
         * 3. PARAMETER MIDTRANS
         * =========================
         */
        $params = [
            'transaction_details' => [
                'order_id'     => $order_id,
                'gross_amount' => (int) $pembayaran->total_biaya,
            ]
        ];

        /**
         * =========================
         * 4. REQUEST SNAP TOKEN
         * =========================
         */
        try {

            Log::info('🔄 REQUEST SNAP', [
                'order_id' => $order_id,
                'amount'   => $pembayaran->total_biaya
            ]);

            $snapToken = Snap::getSnapToken($params);

            if (empty($snapToken)) {
                throw new \Exception("Snap token kosong dari Midtrans");
            }

            /**
             * =========================
             * 5. SIMPAN KE DATABASE
             * =========================
             */
            $pembayaran->update([
                'payment_ref' => $order_id,
                'snap_token'  => $snapToken,
            ]);

            Log::info('✅ SNAP TOKEN BERHASIL', [
                'order_id'   => $order_id,
                'snap_token' => $snapToken
            ]);

            return [
                'order_id'   => $order_id,
                'snap_token' => $snapToken
            ];

        } catch (\Exception $e) {

            Log::error('❌ MIDTRANS ERROR: ' . $e->getMessage());

            throw new \Exception("Midtrans gagal: " . $e->getMessage());
        }
    }
}