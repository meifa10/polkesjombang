<?php

namespace App\Services;

use Midtrans\Snap;
use Midtrans\Config;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Log;

class PaymentService
{
    /**
     * =========================
     * INIT MIDTRANS
     * =========================
     */
    protected function initMidtrans()
    {
        Config::$serverKey    = config('services.midtrans.server_key');
        Config::$isProduction = config('services.midtrans.is_production', false);
        Config::$isSanitized  = true;
        Config::$is3ds        = true;

        if (empty(Config::$serverKey)) {
            throw new \Exception('Midtrans Server Key belum diset!');
        }
    }

    /**
     * =========================
     * CREATE TRANSACTION
     * =========================
     */
    public function createTransaction($pembayaran)
    {
        $this->initMidtrans();

        /**
         * =========================
         * 1. CEK STATUS
         * =========================
         */
        if ($pembayaran->status === 'lunas') {
            throw new \Exception('Pembayaran sudah lunas.');
        }

        /**
         * =========================
         * 2. JIKA SUDAH ADA TOKEN → PAKAI LAGI
         * =========================
         */
        if ($pembayaran->snap_token && $pembayaran->payment_ref) {
            return [
                'order_id'   => $pembayaran->payment_ref,
                'snap_token' => $pembayaran->snap_token
            ];
        }

        /**
         * =========================
         * 3. BUAT ORDER ID (WAJIB UNIQUE)
         * =========================
         */
        $order_id = 'PAY-' . $pembayaran->id . '-' . strtoupper(Str::random(8));

        /**
         * =========================
         * 4. SIMPAN KE DB DULU (PENTING!)
         * =========================
         */
        $pembayaran->update([
            'payment_ref' => $order_id,
            'snap_token'  => null
        ]);

        /**
         * =========================
         * 5. PARAMETER MIDTRANS
         * =========================
         */
        $params = [
            'transaction_details' => [
                'order_id'     => $order_id,
                'gross_amount' => (int) $pembayaran->total_biaya,
            ],
            'item_details' => [
                [
                    'id'       => $pembayaran->id,
                    'price'    => (int) $pembayaran->total_biaya,
                    'quantity' => 1,
                    'name'     => 'Pembayaran Layanan #' . $pembayaran->id,
                ]
            ],
            'customer_details' => [
                'first_name' => optional($pembayaran->pendaftaran)->nama_pasien ?? 'Pasien',
            ],
        ];

        /**
         * =========================
         * 6. REQUEST KE MIDTRANS
         * =========================
         */
        try {

            $snapToken = Snap::getSnapToken($params);

            /**
             * =========================
             * 7. SIMPAN TOKEN
             * =========================
             */
            $pembayaran->update([
                'snap_token' => $snapToken
            ]);

            return [
                'order_id'   => $order_id,
                'snap_token' => $snapToken
            ];

        } catch (\Exception $e) {

            Log::error('❌ MIDTRANS ERROR: ' . $e->getMessage());

            /**
             * =========================
             * 8. HANDLE DUPLICATE ORDER ID
             * =========================
             */
            if (
                str_contains($e->getMessage(), 'order_id sudah digunakan') ||
                str_contains($e->getMessage(), 'already been taken')
            ) {

                $newOrderId = 'PAY-' . $pembayaran->id . '-' . strtoupper(Str::random(10));

                $pembayaran->update([
                    'payment_ref' => $newOrderId,
                    'snap_token'  => null
                ]);

                return $this->createTransaction($pembayaran->fresh());
            }

            throw new \Exception("Midtrans gagal: " . $e->getMessage());
        }
    }
}