<?php

namespace App\Services;

use Midtrans\Config;
use Midtrans\Snap;
use Illuminate\Support\Facades\Log;

class PaymentService
{
    public function __construct()
    {
        Config::$serverKey = config('services.midtrans.server_key');
        Config::$clientKey = config('services.midtrans.client_key');
        Config::$isProduction = config('services.midtrans.is_production', false);
        Config::$isSanitized = true;
        Config::$is3ds = true;
    }

    /**
     * @param $pembayaran
     * @param int $tarifDokter (Tarif terbaru dari DB)
     * @param int $tarifAdmin (Tarif terbaru dari DB)
     * @param int $total (Total terbaru dari DB)
     */
    public function createTransaction($pembayaran, $tarifDokter, $tarifAdmin, $total)
    {
        try {
            if (!empty($pembayaran->snap_token)) {
                return ['snap_token' => $pembayaran->snap_token];
            }

            $orderId = $pembayaran->payment_ref ?? ('INV-' . time() . '-' . $pembayaran->id);

            $params = [
                'transaction_details' => [
                    'order_id' => $orderId,
                    'gross_amount' => (int)$total,
                ],
                'item_details' => [
                    [
                        'id' => 'DOKTER',
                        'price' => (int)$tarifDokter,
                        'quantity' => 1,
                        'name' => 'Jasa Dokter & Konsultasi'
                    ],
                    [
                        'id' => 'ADMIN',
                        'price' => (int)$tarifAdmin,
                        'quantity' => 1,
                        'name' => 'Administrasi Rumah Sakit'
                    ],
                    [
                        'id' => 'OBAT',
                        'price' => (int)$pembayaran->total_obat,
                        'quantity' => 1,
                        'name' => 'Obat & Farmasi'
                    ]
                ],
                'customer_details' => [
                    'first_name' => optional($pembayaran->pendaftaran)->nama_pasien ?? 'Pasien',
                ],
                'callbacks' => [
                    'finish' => url('/payment/finish'),
                    'error' => url('/payment/error')
                ]
            ];

            $snapToken = Snap::getSnapToken($params);

            $pembayaran->update([
                'snap_token' => $snapToken
            ]);

            return ['snap_token' => $snapToken];

        } catch (\Exception $e) {
            Log::error('MIDTRANS ERROR: ' . $e->getMessage());
            throw new \Exception('Gagal membuat transaksi Midtrans');
        }
    }
}