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
            throw new \Exception("Midtrans Server Key tidak ditemukan di .env");
        }
    }

    public function createTransaction($pembayaran)
    {
        /**
         * =========================
         * 1. VALIDASI DATA
         * =========================
         */
        if (!$pembayaran) {
            throw new \Exception("Data pembayaran tidak ditemukan");
        }

        if ((int)$pembayaran->total_biaya <= 0) {
            throw new \Exception("Total biaya tidak valid");
        }

        /**
         * =========================
         * 2. AMBIL DATA CUSTOMER
         * =========================
         */
        $nama  = optional($pembayaran->pendaftaran)->nama_pasien ?? 'Pasien';
        $email = optional(optional($pembayaran->pendaftaran)->user)->email ?? 'pasien@mail.com';
        $phone = optional($pembayaran->pendaftaran)->no_hp ?? '08123456789';

        /**
         * =========================
         * 3. GENERATE ORDER ID
         * =========================
         */
        $order_id = 'PAY-' . $pembayaran->id . '-' . strtoupper(Str::random(8));

        /**
         * =========================
         * 4. PARAMETER MIDTRANS
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
         * 5. REQUEST SNAP TOKEN
         * =========================
         */
        try {
            Log::info('🔄 REQUEST SNAP KE MIDTRANS', [
                'order_id' => $order_id,
                'gross_amount' => $pembayaran->total_biaya
            ]);

            $snapToken = Snap::getSnapToken($params);

            /**
             * 🔥 VALIDASI WAJIB
             */
            if (empty($snapToken)) {
                throw new \Exception("Snap token kosong dari Midtrans");
            }

            /**
             * =========================
             * 6. SIMPAN KE DATABASE
             * =========================
             */
            $pembayaran->update([
                'payment_ref' => $order_id,
                'snap_token'  => $snapToken,
            ]);

            Log::info('✅ SNAP TOKEN BERHASIL DISIMPAN', [
                'order_id' => $order_id,
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
             * HANDLE DUPLICATE ORDER ID
             * =========================
             */
            if (str_contains($e->getMessage(), 'already been taken')) {
                Log::warning('⚠️ DUPLICATE ORDER ID, RETRYING...');
                return $this->createTransaction($pembayaran);
            }

            /**
             * =========================
             * RESET DATA AGAR BISA RETRY
             * =========================
             */
            $pembayaran->update([
                'payment_ref' => null,
                'snap_token'  => null,
            ]);

            throw new \Exception("Midtrans gagal: " . $e->getMessage());
        }
    }
}