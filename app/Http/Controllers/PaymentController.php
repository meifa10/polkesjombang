<?php

namespace App\Http\Controllers;

use App\Models\Pembayaran;
use App\Services\PaymentService;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;

class PaymentController extends Controller
{
    /**
     * =========================================
     * HALAMAN PEMBAYARAN (MIDTRANS SNAP)
     * =========================================
     */
    public function pay($id, PaymentService $paymentService)
    {
        /**
         * =========================================
         * 1. CEK USER LOGIN
         * =========================================
         */
        $user = Auth::user();

        if (!$user) {
            return redirect()->route('login')
                ->with('error', 'Silakan login terlebih dahulu');
        }

        /**
         * =========================================
         * 2. AMBIL DATA PEMBAYARAN (TANPA FILTER STATUS DULU)
         */
        $pembayaran = Pembayaran::with('pendaftaran')->find($id);

        if (!$pembayaran) {
            return redirect()->route('dashboard')
                ->with('error', 'Data pembayaran tidak ditemukan');
        }

        /**
         * =========================================
         * 3. JIKA SUDAH LUNAS → STOP
         */
        if ($pembayaran->status === 'lunas') {
            return redirect()->route('dashboard')
                ->with('success', 'Pembayaran sudah lunas');
        }

        /**
         * =========================================
         * 4. LOG DEBUG
         */
        Log::info('PAYMENT REQUEST', [
            'user_id'       => $user->id,
            'pembayaran_id' => $pembayaran->id,
            'payment_ref'   => $pembayaran->payment_ref,
            'snap_token'    => $pembayaran->snap_token,
        ]);

        /**
         * =========================================
         * 5. JIKA SUDAH ADA SNAP TOKEN → PAKAI ULANG
         */
        if ($pembayaran->snap_token) {

            return view('payment.pay', [
                'snapToken' => $pembayaran->snap_token,
                'pembayaran'=> $pembayaran
            ]);
        }

        /**
         * =========================================
         * 6. BUAT TRANSAKSI BARU KE MIDTRANS
         */
        try {

            $result = $paymentService->createTransaction($pembayaran);

        } catch (\Exception $e) {

            Log::error('MIDTRANS ERROR: ' . $e->getMessage());

            return redirect()->route('dashboard')
                ->with('error', 'Gagal membuat transaksi pembayaran');
        }

        /**
         * =========================================
         * 7. SIMPAN ORDER ID & SNAP TOKEN
         */
        $pembayaran->payment_ref = $result['order_id'];
        $pembayaran->snap_token  = $result['snap_token'];
        $pembayaran->save();

        /**
         * =========================================
         * 8. TAMPILKAN SNAP
         */
        return view('payment.pay', [
            'snapToken' => $result['snap_token'],
            'pembayaran'=> $pembayaran
        ]);
    }
}