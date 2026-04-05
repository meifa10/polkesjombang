<?php

namespace App\Services;

use Midtrans\Snap;
use Midtrans\Config;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;

class PaymentService
{
    public function createTransaction($pembayaran)
    {
        /**
         * =========================================
         * 1. CONFIG MIDTRANS
         * =========================================
         */
        Config::$serverKey    = env('MIDTRANS_SERVER_KEY');
        Config::$isProduction = env('MIDTRANS_IS_PRODUCTION', false);
        Config::$isSanitized  = true;
        Config::$is3ds        = true;

        /**
         * =========================================
         * 2. CEK JIKA SUDAH ADA ORDER ID
         * =========================================
         */
        if ($pembayaran->payment_ref) {
            $order_id = $pembayaran->payment_ref;
        } else {
            // 🔥 pakai UUID biar 100% unik
            $order_id = 'PAY-' . $pembayaran->id . '-' . Str::uuid();
        }

        /**
         * =========================================
         * 3. PARAMETER MIDTRANS
         * =========================================
         */
        $params = [
            'transaction_details' => [
                'order_id'     => $order_id,
                'gross_amount' => (int) $pembayaran->total_biaya,
            ],
            'customer_details' => [
                'first_name' => optional($pembayaran->pendaftaran)->nama_pasien ?? 'Pasien',
            ],
        ];

        /**
         * =========================================
         * 4. REQUEST KE MIDTRANS
         * =========================================
         */
        try {

            $snapToken = Snap::getSnapToken($params);

            return [
                'order_id'   => $order_id,
                'snap_token' => $snapToken
            ];

        } catch (\Exception $e) {

            Log::error("❌ MIDTRANS ERROR: " . $e->getMessage());

            throw new \Exception($e->getMessage());
        }
    }
}