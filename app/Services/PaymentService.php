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
        Config::$isProduction = false;
        Config::$isSanitized  = true;
        Config::$is3ds        = true;
    }

    public function createTransaction($pembayaran)
    {
        /**
         * =========================
         * 1. PAKAI ORDER ID YANG SUDAH ADA
         * =========================
         */
        if ($pembayaran->payment_ref) {
            $order_id = $pembayaran->payment_ref;
        } else {
            $order_id = 'PAY-' . $pembayaran->id . '-' . time();

            $pembayaran->payment_ref = $order_id;
            $pembayaran->save();
        }

        /**
         * =========================
         * 2. PARAMETER MIDTRANS
         * =========================
         */
        $params = [
            'transaction_details' => [
                'order_id'     => $order_id,
                'gross_amount' => (int) $pembayaran->total_biaya,
            ],
        ];

        /**
         * =========================
         * 3. REQUEST SNAP
         * =========================
         */
        $snapToken = Snap::getSnapToken($params);

        return [
            'order_id'   => $order_id,
            'snap_token' => $snapToken
        ];
    }
}