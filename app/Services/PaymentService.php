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
        Config::$serverKey    = env('MIDTRANS_SERVER_KEY');
        Config::$isProduction = env('MIDTRANS_IS_PRODUCTION', false);
        Config::$isSanitized  = true;
        Config::$is3ds        = true;

        if (empty(Config::$serverKey)) {
            Log::error("❌ MIDTRANS ERROR: Server Key kosong!");
        }
    }

    public function createTransaction($pembayaran)
    {
        /**
         * =========================
         * 1. PASTIKAN RELASI AMAN
         * =========================
         */
        $nama  = optional($pembayaran->pendaftaran)->nama_pasien ?? 'Pasien';
        $email = optional(optional($pembayaran->pendaftaran)->user)->email ?? 'pasien@mail.com';
        $phone = optional($pembayaran->pendaftaran)->no_hp ?? '08123456789';

        /**
         * =========================
         * 2. GENERATE ORDER ID BARU (SELALU FRESH)
         * =========================
         */
        $order_id = 'PAY-' . $pembayaran->id . '-' . strtoupper(Str::random(8));

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

            'customer_details' => [
                'first_name' => $nama,
                'email'      => $email,
                'phone'      => $phone,
            ],

            'item_details' => [
                [
                    'id'       => 'PEMB-' . $pembayaran->id,
                    'price'    => (int) $pembayaran->total_biaya,
                    'quantity' => 1,
                    'name'     => "Pembayaran #" . $pembayaran->id,
                ]
            ],
        ];

        /**
         * =========================
         * 4. REQUEST SNAP TOKEN
         * =========================
         */
        try {
            Log::info('🔄 REQUEST SNAP...', [
                'order_id' => $order_id
            ]);

            $snapToken = Snap::getSnapToken($params);

            /**
             * =========================
             * 5. SIMPAN KE DATABASE
             * =========================
             */
            $pembayaran->update([
                'payment_ref' => $order_id,
                'snap_token'  => $snapToken,
            ]);

            Log::info('✅ SNAP TOKEN BERHASIL', [
                'order_id' => $order_id,
                'token' => $snapToken
            ]);

            return [
                'order_id'   => $order_id,
                'snap_token' => $snapToken
            ];

        } catch (\Exception $e) {

            Log::error('❌ MIDTRANS ERROR: ' . $e->getMessage());

            /**
             * 🔥 HANDLE DUPLICATE ORDER ID
             */
            if (str_contains($e->getMessage(), 'already been taken')) {
                return $this->createTransaction($pembayaran); // retry otomatis
            }

            throw new \Exception("Midtrans gagal: " . $e->getMessage());
        }
    }
}