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
    }

    public function createTransaction($pembayaran)
    {
        /**
         * =========================
         * 1. CEK TOKEN SUDAH ADA
         * =========================
         */
        if (!empty($pembayaran->snap_token)) {

            Log::info('♻️ PAKAI TOKEN LAMA', [
                'order_id' => $pembayaran->payment_ref
            ]);

            return [
                'order_id'   => $pembayaran->payment_ref,
                'snap_token' => $pembayaran->snap_token
            ];
        }

        /**
         * =========================
         * 2. BUAT ORDER ID (HANYA SEKALI)
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
         * 4. REQUEST SNAP (HANYA SEKALI)
         * =========================
         */
        try {

            Log::info('🔄 REQUEST SNAP BARU', [
                'order_id' => $order_id
            ]);

            $snapToken = Snap::getSnapToken($params);

            /**
             * =========================
             * 5. SIMPAN KE DB
             * =========================
             */
            $pembayaran->update([
                'payment_ref' => $order_id,
                'snap_token'  => $snapToken,
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