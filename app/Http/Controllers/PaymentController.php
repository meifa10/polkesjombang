<?php

namespace App\Http\Controllers;

use App\Models\Pembayaran;
use App\Services\PaymentService;
use Illuminate\Support\Facades\Auth;

class PaymentController extends Controller
{
    public function pay($id, PaymentService $paymentService)
    {
        $user = Auth::user();

        $pembayaran = Pembayaran::where('id', $id)
            ->where('status', 'belum_lunas')
            ->whereHas('pendaftaran', function ($q) use ($user) {
                $q->where('nama_pasien', $user->name);
            })
            ->firstOrFail();

        $result = $paymentService->createTransaction($pembayaran);

        return view('payment.pay', [
            'snapToken' => $result['snap_token'],
            'pembayaran' => $pembayaran
        ]);
    }
}