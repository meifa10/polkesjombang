<?php

namespace App\Services;

use Midtrans\Snap;
use Midtrans\Config;
use Illuminate\Support\Facades\Log;

class PaymentService
{
    /**
     * INIT MIDTRANS
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
     * CREATE TRANSACTION (FIX)
     */
    public function createTransaction($pembayaran)
    {
        $this->initMidtrans();

        // =========================
        // CEK STATUS
        // =========================
        if ($pembayaran->status === 'lunas') {
            throw new \Exception('Sudah lunas');
        }

        // =========================
        // PAKAI ORDER ID DARI ADMIN (PENTING!)
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
        // PARAMETER MIDTRANS
        // =========================
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

        // =========================
        // REQUEST SNAP
        // =========================
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

            Log::error('MIDTRANS ERROR: ' . $e->getMessage());

            throw new \Exception("Midtrans gagal: " . $e->getMessage());
        }
    }
}