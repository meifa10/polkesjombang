<?php

namespace App\Services;

use Midtrans\Snap;
use Midtrans\Config;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Log;

class PaymentService
{
    /**
     * Set konfigurasi Midtrans secara statis.
     * Menggunakan config() lebih aman daripada env() langsung.
     */
    protected function initMidtrans()
    {
        Config::$serverKey    = config('services.midtrans.server_key');
        Config::$isProduction = config('services.midtrans.is_production', false);
        Config::$isSanitized  = true;
        Config::$is3ds        = true;
    }

    public function createTransaction($pembayaran)
    {
        // 1. Inisialisasi Kunci API
        $this->initMidtrans();

        // Pastikan Key tidak kosong
        if (!Config::$serverKey) {
            Log::error('Midtrans Server Key is missing. Check your .env and config/services.php');
            throw new \Exception('Konfigurasi pembayaran belum lengkap.');
        }

        /**
         * =========================
         * 2. CEK SNAP TOKEN DULU
         * =========================
         */
        if ($pembayaran->snap_token && $pembayaran->status === 'belum_lunas') {
            return [
                'order_id'   => $pembayaran->payment_ref,
                'snap_token' => $pembayaran->snap_token
            ];
        }

        /**
         * =========================
         * 3. CEK / BUAT ORDER ID
         * =========================
         */
        if ($pembayaran->payment_ref && $pembayaran->status === 'belum_lunas') {
            $order_id = $pembayaran->payment_ref;
        } else {
            // Gunakan prefix yang jelas, ID pembayaran, dan string acak
            $order_id = 'PAY-' . $pembayaran->id . '-' . Str::upper(Str::random(6));
            
            $pembayaran->update([
                'payment_ref' => $order_id,
                'snap_token'  => null
            ]);
        }

        /**
         * =========================
         * 4. PARAMETER MIDTRANS
         * =========================
         */
        $params = [
            'transaction_details' => [
                'order_id'     => $order_id,
                'gross_amount' => (int) $pembayaran->total_biaya, // Pastikan integer
            ],
            // Opsional: Tambahkan customer_details jika ingin lebih profesional
            'item_details' => [
                [
                    'id'       => $pembayaran->id,
                    'price'    => (int) $pembayaran->total_biaya,
                    'quantity' => 1,
                    'name'     => "Pembayaran Polkes Jombang #" . $pembayaran->id,
                ]
            ],
        ];

        /**
         * =========================
         * 5. REQUEST SNAP
         * =========================
         */
        try {
            $snapToken = Snap::getSnapToken($params);

            // Simpan token ke database
            $pembayaran->update([
                'snap_token' => $snapToken
            ]);

            return [
                'order_id'   => $order_id,
                'snap_token' => $snapToken
            ];

        } catch (\Exception $e) {
            // Log error untuk mempermudah debug di storage/logs/laravel.log
            Log::error('Midtrans Error: ' . $e->getMessage());

            // 🔥 HANDLE DUPLICATE ORDER ID
            if (str_contains($e->getMessage(), 'sudah digunakan') || str_contains($e->getMessage(), 'used')) {
                
                // Jika duplikat, buat ID baru dan coba sekali lagi (rekursif)
                $new_order_id = 'PAY-' . $pembayaran->id . '-' . Str::upper(Str::random(9));
                $pembayaran->update(['payment_ref' => $new_order_id]);
                
                // Refresh data model dan coba lagi
                return $this->createTransaction($pembayaran->fresh());
            }

            throw $e;
        }
    }
}