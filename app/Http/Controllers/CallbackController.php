<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Pembayaran;
use Midtrans\Notification;
use Midtrans\Config;

class CallbackController extends Controller
{

    public function handle(Request $request)
    {

        Config::$serverKey = env('MIDTRANS_SERVER_KEY');
        Config::$isProduction = false;

        $notif = new Notification();

        $orderId = $notif->order_id;
        $transactionStatus = $notif->transaction_status;
        $paymentType = $notif->payment_type;

        $explode = explode('-', $orderId);
        $id = $explode[1];

        $pembayaran = Pembayaran::find($id);

        if(!$pembayaran){
            return response()->json(['message'=>'Data tidak ditemukan'],404);
        }

        if($transactionStatus == 'settlement'){
            $pembayaran->status = 'lunas';
            $pembayaran->paid_by = $paymentType;
            $pembayaran->tanggal_bayar = now();
        }

        if(
            $transactionStatus == 'expire' ||
            $transactionStatus == 'cancel' ||
            $transactionStatus == 'deny'
        ){
            $pembayaran->status = 'gagal';
        }

        $pembayaran->save();

        return response()->json(['message'=>'OK']);
    }
}