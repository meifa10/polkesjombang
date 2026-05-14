<?php

namespace App\Services;

use Midtrans\Config;
use Midtrans\Snap;

use Illuminate\Support\Facades\Log;

class PaymentService
{

    /*
    |--------------------------------------------------------------------------
    | INIT MIDTRANS
    |--------------------------------------------------------------------------
    */

    public function __construct()
    {

        Config::$serverKey =
            config('services.midtrans.server_key');

        Config::$clientKey =
            config('services.midtrans.client_key');

        Config::$isProduction =
            config(
                'services.midtrans.is_production',
                false
            );

        Config::$isSanitized = true;

        Config::$is3ds = true;
    }


    /*
    |--------------------------------------------------------------------------
    | CREATE TRANSACTION
    |--------------------------------------------------------------------------
    */

    public function createTransaction($pembayaran)
    {

        try {

            /*
            |--------------------------------------------------------------------------
            | CEK TOKEN SUDAH ADA
            |--------------------------------------------------------------------------
            */

            if (
                !empty($pembayaran->snap_token)
            ) {

                return [

                    'snap_token' =>
                        $pembayaran->snap_token

                ];
            }


            /*
            |--------------------------------------------------------------------------
            | ORDER ID
            |--------------------------------------------------------------------------
            */

            $orderId =
                $pembayaran->payment_ref
                ?? ('INV-' . time() . '-' . $pembayaran->id);


            /*
            |--------------------------------------------------------------------------
            | TOTAL
            |--------------------------------------------------------------------------
            */

            $grossAmount =
                (int) $pembayaran->total_biaya;


            /*
            |--------------------------------------------------------------------------
            | PARAMETER MIDTRANS
            |--------------------------------------------------------------------------
            */

            $params = [

                'transaction_details' => [

                    'order_id' =>
                        $orderId,

                    'gross_amount' =>
                        $grossAmount

                ],

                'customer_details' => [

                    'first_name' =>
                        optional(
                            $pembayaran->pendaftaran
                        )->nama_pasien
                        ?? 'Pasien',

                ],

                'callbacks' => [

                    'finish' =>
                        url('/payment/finish'),

                    'error' =>
                        url('/payment/error')

                ]

            ];


            /*
            |--------------------------------------------------------------------------
            | GENERATE SNAP TOKEN
            |--------------------------------------------------------------------------
            */

            $snapToken =
                Snap::getSnapToken($params);


            /*
            |--------------------------------------------------------------------------
            | SIMPAN TOKEN
            |--------------------------------------------------------------------------
            */

            $pembayaran->update([

                'snap_token' =>
                    $snapToken

            ]);


            /*
            |--------------------------------------------------------------------------
            | RETURN TOKEN
            |--------------------------------------------------------------------------
            */

            return [

                'snap_token' =>
                    $snapToken

            ];

        } catch (\Exception $e) {

            /*
            |--------------------------------------------------------------------------
            | LOG ERROR
            |--------------------------------------------------------------------------
            */

            Log::error(
                'MIDTRANS ERROR : '
                . $e->getMessage()
            );

            throw new \Exception(
                'Gagal membuat transaksi Midtrans'
            );
        }
    }
}