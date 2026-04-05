<?php

namespace App\Services;

use Midtrans\Snap;
use Midtrans\Config;
use Illuminate\Support\Facades\Log;

class PaymentService
{
    /**
     * =========================================
     * CREATE MIDTRANS TRANSACTION (FIXED)
     * =========================================
     */
    public function createTransaction($pembayaran)
    {
        /**
         * =========================================
         * 1. CONFIG MIDTRANS
         * =========================================
         */
        Config::$serverKey    = config('services.midtrans.server_key');
        Config::$isProduction = config('services.midtrans.is_production', false);
        Config::$isSanitized  = true;
        Config::$is3ds        = true;

        /**
         * =========================================
         * 2. GUNAKAN ORDER_ID YANG SAMA (PENTING!)
         * =========================================
         */
        if (!$pembayaran->payment_ref) {

            // Generate hanya sekali
            $orderId = 'PAY-' . $pembayaran->id . '-' . time();

            $pembayaran->payment_ref = $orderId;
            $pembayaran->save();

            Log::info("🆕 GENERATE ORDER ID: " . $orderId);

        } else {

            // Pakai yang sudah ada (ANTI MISMATCH)
            $orderId = $pembayaran->payment_ref;

            Log::info("♻️ PAKAI ORDER ID LAMA: " . $orderId);
        }

        /**
         * =========================================
         * 3. PARAMETER MIDTRANS
         * =========================================
         */
        $params = [
            'transaction_details' => [
                'order_id'     => $orderId,
                'gross_amount' => (int) $pembayaran->total_biaya,
            ],

            // (OPSIONAL) DETAIL CUSTOMER
            'customer_details' => [
                'first_name' => optional($pembayaran->pendaftaran)->nama_pasien ?? 'Pasien',
            ],

            // (OPSIONAL) ITEM DETAIL
            'item_details' => [
                [
                    'id'       => $pembayaran->id,
                    'price'    => (int) $pembayaran->total_biaya,
                    'quantity' => 1,
                    'name'     => 'Pembayaran Poli',
                ]
            ],
        ];

        /**
         * =========================================
         * 4. REQUEST SNAP TOKEN
         * =========================================
         */
        try {

            $snapToken = Snap::getSnapToken($params);

            Log::info("✅ SNAP TOKEN BERHASIL: " . $orderId);

            return [
                'order_id'   => $orderId,
                'snap_token' => $snapToken
            ];

        } catch (\Exception $e) {

            Log::error("❌ MIDTRANS ERROR DETAIL: " . $e->getMessage());

            throw $e; // sementara biar kelihatan error asli
        }
    }
}