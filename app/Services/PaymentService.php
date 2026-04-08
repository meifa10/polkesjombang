<?php

namespace App\Services;

use Midtrans\Snap;
use Midtrans\Config;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Log;

class PaymentService
{
    protected function initMidtrans()
    {
        Config::$serverKey    = config('services.midtrans.server_key');
        Config::$isProduction = config('services.midtrans.is_production', false);
        Config::$isSanitized  = true;
        Config::$is3ds        = true;
    }

    public function createTransaction($pembayaran)
    {
        $this->initMidtrans();

        if (!Config::$serverKey) {
            throw new \Exception('Konfigurasi Midtrans (Server Key) kosong!');
        }

        /**
         * 1. PROTEKSI: JANGAN PROSES KALAU SUDAH LUNAS
         * Di log kamu, statusnya sudah 'settlement'. Jika di DB masih 'belum_lunas',
         * kita harus cegah kirim request lagi ke Midtrans dengan Order ID yang sama.
         */
        if ($pembayaran->status === 'lunas') {
            throw new \Exception('Pembayaran ini sudah selesai.');
        }

        /**
         * 2. GUNAKAN SNAP TOKEN LAMA JIKA ADA
         * Supaya tidak kena error 'order_id taken'
         */
        if ($pembayaran->snap_token) {
            return [
                'order_id'   => $pembayaran->payment_ref,
                'snap_token' => $pembayaran->snap_token
            ];
        }

        /**
         * 3. BUAT ORDER ID BARU JIKA BELUM ADA
         */
        $order_id = 'PAY-' . $pembayaran->id . '-' . Str::upper(Str::random(6));
        
        // Simpan referensi awal
        $pembayaran->update([
            'payment_ref' => $order_id
        ]);

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
                    'name'     => "Layanan Polkes Jombang #" . $pembayaran->id,
                ]
            ],
        ];

        try {
            $snapToken = Snap::getSnapToken($params);

            $pembayaran->update([
                'snap_token' => $snapToken
            ]);

            return [
                'order_id'   => $order_id,
                'snap_token' => $snapToken
            ];

        } catch (\Exception $e) {
            Log::error('Midtrans Error: ' . $e->getMessage());

            // Jika tetap error duplikat, paksa ganti Order ID dan coba sekali lagi
            if (str_contains($e->getMessage(), 'already been taken')) {
                $new_id = 'PAY-' . $pembayaran->id . '-' . Str::upper(Str::random(9));
                $pembayaran->update(['payment_ref' => $new_id, 'snap_token' => null]);
                return $this->createTransaction($pembayaran->fresh());
            }

            throw $e;
        }
    }
}