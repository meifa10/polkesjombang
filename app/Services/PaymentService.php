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
         * 1. JIKA TOKEN SUDAH ADA → PAKAI ULANG
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
         * 2. JIKA ORDER ID SUDAH ADA TAPI TOKEN HILANG
         * =========================
         */
        if (!empty($pembayaran->payment_ref) && empty($pembayaran->snap_token)) {

            Log::error('❌ TOKEN HILANG TAPI ORDER SUDAH ADA', [
                'order_id' => $pembayaran->payment_ref
            ]);

            throw new \Exception("Token pembayaran hilang. Silakan reset pembayaran.");
        }

        /**
         * =========================
         * 3. BUAT TRANSAKSI BARU (HANYA SEKALI)
         * =========================
         */
        $order_id = 'PAY-' . $pembayaran->id . '-' . time();

        $params = [
            'transaction_details' => [
                'order_id'     => $order_id,
                'gross_amount' => (int) $pembayaran->total_biaya,
            ]
        ];

        try {

            Log::info('🔄 CREATE TRANSACTION BARU', [
                'order_id' => $order_id
            ]);

            $snapToken = Snap::getSnapToken($params);

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