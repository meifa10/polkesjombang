<?php

namespace App\Services;

use Midtrans\Snap;
use Midtrans\Config;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;

class PaymentService
{
    public function __construct()
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

        if (empty(Config::$serverKey)) {
            Log::error("❌ MIDTRANS ERROR: Server Key tidak ditemukan di .env");
        }
    }

    /**
     * =========================================
     * 2. CREATE TRANSACTION (FINAL FIX)
     * =========================================
     */
    public function createTransaction($pembayaran)
    {
        /**
         * =========================================
         * 🔥 PENTING: JANGAN SELALU GENERATE BARU
         * =========================================
         */
        if ($pembayaran->payment_ref) {

            // ✅ gunakan yang sudah ada (biar callback cocok)
            $order_id = $pembayaran->payment_ref;

        } else {

            // ✅ generate SEKALI SAJA
            $order_id = 'PAY-' . $pembayaran->id . '-' . strtoupper(Str::random(10));

            // simpan langsung ke DB
            $pembayaran->payment_ref = $order_id;
            $pembayaran->save();
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
                'email'      => optional($pembayaran->pendaftaran->user)->email ?? 'guest@email.com',
                'phone'      => optional($pembayaran->pendaftaran)->no_hp ?? '08123456789',
            ],

            'item_details' => [
                [
                    'id'       => 'PEMB-' . $pembayaran->id,
                    'price'    => (int) $pembayaran->total_biaya,
                    'quantity' => 1,
                    'name'     => "Pembayaran Layanan #" . $pembayaran->id,
                ]
            ],

            'callbacks' => [
                'finish'  => url('/payment/finish'),
                'error'   => url('/payment/error'),
                'pending' => url('/payment/pending'),
            ],
        ];

        /**
         * =========================================
         * 4. REQUEST KE MIDTRANS
         * =========================================
         */
        try {

            $snapToken = Snap::getSnapToken($params);

            Log::info('✅ MIDTRANS CREATED', [
                'pembayaran_id' => $pembayaran->id,
                'order_id'      => $order_id,
                'snap_token'    => $snapToken,
            ]);

            return [
                'order_id'   => $order_id,
                'snap_token' => $snapToken
            ];

        } catch (\Exception $e) {

            Log::error("❌ MIDTRANS ERROR: " . $e->getMessage(), [
                'pembayaran_id' => $pembayaran->id,
                'order_id'      => $order_id ?? null,
                'params'        => $params
            ]);

            throw new \Exception("Midtrans gagal: " . $e->getMessage());
        }
    }
}