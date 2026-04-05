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
         * =============================================================
         * 1. KONFIGURASI GLOBAL MIDTRANS
         * =============================================================
         */
        Config::$serverKey    = env('MIDTRANS_SERVER_KEY');
        Config::$isProduction = env('MIDTRANS_IS_PRODUCTION', false);
        Config::$isSanitized  = true;
        Config::$is3ds         = true;

        // Validasi jika Server Key kosong
        if (empty(Config::$serverKey)) {
            Log::error("❌ MIDTRANS ERROR: Server Key tidak ditemukan di file .env");
        }
    }

    /**
     * Membuat transaksi ke Midtrans dan mendapatkan Snap Token
     */
    public function createTransaction($pembayaran)
    {
        /**
         * =============================================================
         * 2. GENERATE ORDER ID UNIK (SOLUSI ANTI DUPLIKAT)
         * =============================================================
         * Format: PAY-[ID]-[RANDOM]
         * Contoh: PAY-7-A1B2C3D4E5
         * Menggunakan random string 10 digit agar tidak melebihi limit 50 karakter Midtrans.
         */
        $order_id = 'PAY-' . $pembayaran->id . '-' . strtoupper(Str::random(10));

        /**
         * =============================================================
         * 3. PENYUSUNAN PARAMETER
         * =============================================================
         */
        $params = [
            'transaction_details' => [
                'order_id'     => $order_id,
                'gross_amount' => (int) $pembayaran->total_biaya,
            ],

            'customer_details' => [
                'first_name' => optional($pembayaran->pendaftaran)->nama_pasien ?? 'Pasien',
                'email'      => optional($pembayaran->pendaftaran->user)->email ?? null,
                'phone'      => optional($pembayaran->pendaftaran)->no_hp ?? null,
            ],

            // Item details membantu struk Midtrans terlihat lebih rapi
            'item_details' => [
                [
                    'id'       => 'PEMB-' . $pembayaran->id,
                    'price'    => (int) $pembayaran->total_biaya,
                    'quantity' => 1,
                    'name'     => "Pembayaran Layanan Kesehatan #" . $pembayaran->id,
                ]
            ],

            /**
             * Redirection setelah pembayaran selesai di halaman Snap
             */
            'callbacks' => [
                'finish' => url('/payment/finish'),
                'error'  => url('/payment/error'),
                'pending'=> url('/payment/pending'),
            ],

            // Opsional: Batasi metode pembayaran jika perlu
            // 'enabled_payments' => ['credit_card', 'bca_va', 'bni_va', 'bri_va', 'gopay', 'shopeepay'],
        ];

        /**
         * =============================================================
         * 4. REQUEST KE API MIDTRANS
         * =============================================================
         */
        try {
            $snapToken = Snap::getSnapToken($params);

            Log::info('✅ MIDTRANS TRANSACTION CREATED', [
                'pembayaran_id' => $pembayaran->id,
                'order_id'      => $order_id,
                'snap_token'    => $snapToken,
            ]);

            return [
                'order_id'   => $order_id,
                'snap_token' => $snapToken
            ];

        } catch (\Exception $e) {
            Log::error("❌ MIDTRANS API ERROR: " . $e->getMessage(), [
                'pembayaran_id' => $pembayaran->id,
                'params'        => $params
            ]);

            // Melemparkan exception agar ditangkap oleh Controller
            throw new \Exception("Gagal berkomunikasi dengan Midtrans: " . $e->getMessage());
        }
    }
}