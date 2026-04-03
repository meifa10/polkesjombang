<?php

namespace App\Services;

use Midtrans\Snap;
use Midtrans\Config;

class PaymentService
{

    public function createTransaction($pembayaran)
    {

        Config::$serverKey = env('MIDTRANS_SERVER_KEY');
        Config::$isProduction = false;
        Config::$isSanitized = true;
        Config::$is3ds = true;

        $order_id = 'PAY-'.$pembayaran->id.'-'.time();

        $params = [
            'transaction_details' => [
                'order_id' => $order_id,
                'gross_amount' => $pembayaran->total_biaya
            ]
        ];

        $snapToken = Snap::getSnapToken($params);

        return [
            'order_id'=>$order_id,
            'snap_token'=>$snapToken
        ];
    }

}