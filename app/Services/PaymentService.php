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
         * 2. GENERATE ORDER ID (WAJIB SELALU BARU)
         * =========================================
         */
        $order_id = 'PAY-' . $pembayaran->id . '-' . Str::uuid();

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

            // 🔥 OPTIONAL (TAPI DISARANKAN)
            'callbacks' => [
                'finish' => url('/payment/finish'),
            ],
        ];

        /**
         * =========================================
         * 4. REQUEST SNAP TOKEN
         * =========================================
         */
        try {

            $snapToken = Snap::getSnapToken($params);

            Log::info('MIDTRANS CREATE SUCCESS', [
                'order_id' => $order_id,
                'snap'     => $snapToken,
            ]);

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