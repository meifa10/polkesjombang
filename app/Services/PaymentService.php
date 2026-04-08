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
         * 1. CEK SNAP TOKEN DULU
         * =========================
         */
        if ($pembayaran->snap_token && $pembayaran->status === 'belum_lunas') {
            return [
                'order_id'   => $pembayaran->payment_ref,
                'snap_token' => $pembayaran->snap_token
            ];
        }

        /**
         * =========================
         * 2. CEK / BUAT ORDER ID
         * =========================
         */
        if ($pembayaran->payment_ref && $pembayaran->status === 'belum_lunas') {
            $order_id = $pembayaran->payment_ref;
        } else {
            $order_id = 'PAY-' . $pembayaran->id . '-' . Str::upper(Str::random(6));

            $pembayaran->payment_ref = $order_id;
            $pembayaran->snap_token  = null;
            $pembayaran->save();
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
            ],
        ];

        /**
         * =========================
         * 4. REQUEST SNAP
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

            // 🔥 HANDLE DUPLICATE ORDER ID
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