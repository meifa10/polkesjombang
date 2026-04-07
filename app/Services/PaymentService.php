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
        // ✅ 1. kalau sudah ada token → pakai ulang
        if (!empty($pembayaran->snap_token)) {
            return [
                'order_id'   => $pembayaran->payment_ref,
                'snap_token' => $pembayaran->snap_token,
            ];
        }

        // ✅ 2. kalau belum ada → buat sekali saja
        $order_id = 'PAY-' . $pembayaran->id . '-' . time();

        $params = [
            'transaction_details' => [
                'order_id'     => $order_id,
                'gross_amount' => (int) $pembayaran->total_biaya,
            ],
        ];

        try {
            $snapToken = Snap::getSnapToken($params);

            // ✅ simpan ke DB SEKALI
            $pembayaran->update([
                'payment_ref' => $order_id,
                'snap_token'  => $snapToken,
            ]);

            return [
                'order_id'   => $order_id,
                'snap_token' => $snapToken,
            ];

        } catch (\Exception $e) {
            Log::error('❌ MIDTRANS ERROR: ' . $e->getMessage());
            throw new \Exception("Midtrans gagal: " . $e->getMessage());
        }
    }
}