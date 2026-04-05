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
         * 1. KONFIGURASI GLOBAL
         * =========================================
         */
        Config::$serverKey    = env('MIDTRANS_SERVER_KEY');
        Config::$isProduction = env('MIDTRANS_IS_PRODUCTION', false);
        Config::$isSanitized  = true;
        Config::$is3ds         = true;

        if (empty(Config::$serverKey)) {
            Log::error("❌ MIDTRANS ERROR: Server Key tidak ditemukan di file .env");
        }
    }

    /**
     * =========================================
     * 2. CREATE TRANSACTION (HIGH RELIABILITY)
     * =========================================
     */
    public function createTransaction($pembayaran)
    {
        /**
         * -----------------------------------------
         * 🔥 PENANGANAN ORDER ID (SINKRONISASI DB)
         * -----------------------------------------
         */
        if (!empty($pembayaran->payment_ref)) {
            // Gunakan yang sudah ada di DB agar callback Midtrans tepat sasaran
            $order_id = $pembayaran->payment_ref;
        } else {
            // Generate baru jika benar-benar kosong
            // Gunakan random 10 digit agar total karakter aman dari limit 50 Midtrans
            $order_id = 'PAY-' . $pembayaran->id . '-' . strtoupper(Str::random(10));
            
            // Langsung update database agar ID ini terkunci
            $pembayaran->update(['payment_ref' => $order_id]);
        }

        /**
         * -----------------------------------------
         * 3. PENYUSUNAN PARAMETER (SAFE MODE)
         * -----------------------------------------
         */
        $params = [
            'transaction_details' => [
                'order_id'     => $order_id,
                'gross_amount' => (int) $pembayaran->total_biaya,
            ],

            'customer_details' => [
                // Menggunakan data pendaftaran, jika null beri fallback string aman
                'first_name' => $pembayaran->pendaftaran->nama_pasien ?? 'Pasien',
                'email'      => $pembayaran->pendaftaran->user->email ?? 'pasien@polkes.tech',
                'phone'      => $pembayaran->pendaftaran->no_hp ?? '08123456789',
            ],

            'item_details' => [
                [
                    'id'       => 'PEMB-' . $pembayaran->id,
                    'price'    => (int) $pembayaran->total_biaya,
                    'quantity' => 1,
                    'name'     => "Biaya Layanan Kesehatan #" . $pembayaran->id,
                ]
            ],

            // Redirection Callbacks (Client Side)
            'callbacks' => [
                'finish'  => url('/payment/finish'),
                'error'   => url('/payment/error'),
                'pending' => url('/payment/pending'),
            ],
        ];

        /**
         * -----------------------------------------
         * 4. EKSEKUSI SNAP TOKEN
         * -----------------------------------------
         */
        try {
            // Request Snap Token ke API Midtrans
            $snapToken = Snap::getSnapToken($params);

            Log::info('✅ MIDTRANS SNAP TOKEN GENERATED', [
                'order_id'   => $order_id,
                'pembayaran' => $pembayaran->id
            ]);

            return [
                'order_id'   => $order_id,
                'snap_token' => $snapToken
            ];

        } catch (\Exception $e) {
            // Jika gagal karena Order ID duplikat di server Midtrans
            if (str_contains($e->getMessage(), 'already been taken')) {
                Log::warning("⚠️ ORDER ID TAKEN: Regenerating for ID " . $pembayaran->id);
                
                // Reset ID di database agar di percobaan berikutnya generate yang baru
                $pembayaran->update(['payment_ref' => null, 'snap_token' => null]);
            }

            Log::error("❌ MIDTRANS API FAIL: " . $e->getMessage(), [
                'order_id' => $order_id,
                'trace'    => $e->getTraceAsString()
            ]);

            throw new \Exception("Koneksi Midtrans Gagal: " . $e->getMessage());
        }
    }
}