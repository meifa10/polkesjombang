<?php

namespace App\Services;

use Midtrans\Snap;
use Midtrans\Config;
use Illuminate\Support\Str;

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
         * 1. CEK ORDER ID
         * =========================
         */
        if ($pembayaran->payment_ref && $pembayaran->status === 'belum_lunas') {
            $order_id = $pembayaran->payment_ref;
        } else {
            // 🔥 BUAT BARU (UNIQUE)
            $order_id = 'PAY-' . $pembayaran->id . '-' . Str::upper(Str::random(6));

            $pembayaran->payment_ref = $order_id;
            $pembayaran->snap_token  = null; // reset token
            $pembayaran->save();
        }

        /**
         * =========================
         * 2. PARAMETER
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
        try {

            $snapToken = Snap::getSnapToken($params);

            $pembayaran->snap_token = $snapToken;
            $pembayaran->save();

            return [
                'order_id'   => $order_id,
                'snap_token' => $snapToken
            ];

        } catch (\Exception $e) {

            // 🔥 kalau order_id duplicate → generate ulang
            if (str_contains($e->getMessage(), 'order_id sudah digunakan')) {

                $order_id = 'PAY-' . $pembayaran->id . '-' . Str::upper(Str::random(8));

                $pembayaran->payment_ref = $order_id;
                $pembayaran->save();

                return $this->createTransaction($pembayaran);
            }

            throw $e;
        }
    }
}