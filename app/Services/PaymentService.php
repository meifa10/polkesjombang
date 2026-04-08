<?php

namespace App\Services;

use Midtrans\Snap;
use Midtrans\Config;
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
            throw new \Exception('Server Key Midtrans belum di-set');
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

        // =========================
        // CEK STATUS
        // =========================
        if ($pembayaran->status === 'lunas') {
            throw new \Exception('Pembayaran sudah lunas.');
        }

        // =========================
        // PAKAI ORDER ID DARI ADMIN
        // =========================
        $order_id = $pembayaran->payment_ref;

        // =========================
        // JIKA SUDAH ADA TOKEN
        // =========================
        if ($pembayaran->snap_token) {
            return [
                'order_id'   => $order_id,
                'snap_token' => $pembayaran->snap_token
            ];
        }

        // =========================
        // PARAMETER MIDTRANS (FIX + CALLBACKS)
        // =========================
        $params = [
            'transaction_details' => [
                'order_id'     => $order_id,
                'gross_amount' => (int) $pembayaran->total_biaya,
            ],

            // 🔥 INI YANG KITA TAMBAHKAN (PENTING BANGET)
            'callbacks' => [
                'finish' => url('/payment/finish'),
                'error'  => url('/payment/error'),
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

        // =========================
        // REQUEST KE MIDTRANS
        // =========================
        try {

            $snapToken = Snap::getSnapToken($params);

            // SIMPAN TOKEN
            $pembayaran->update([
                'snap_token' => $snapToken
            ]);

            return [
                'order_id'   => $order_id,
                'snap_token' => $snapToken
            ];

        } catch (\Exception $e) {

            Log::error('MIDTRANS ERROR: ' . $e->getMessage());

            throw new \Exception("Midtrans gagal: " . $e->getMessage());
        }
    }
}