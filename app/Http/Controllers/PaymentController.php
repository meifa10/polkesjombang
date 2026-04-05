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
         * 2. AMBIL DATA PEMBAYARAN
         * =========================================
         */
        $pembayaran = Pembayaran::with('pendaftaran')
            ->where('id', $id)
            ->where('status', 'belum_lunas')
            ->first();

        if (!$pembayaran) {
            return redirect()->route('dashboard')
                ->with('error', 'Data pembayaran tidak ditemukan atau sudah lunas');
        }

        /**
         * =========================================
         * 3. LOG DEBUG (OPTIONAL)
         * =========================================
         */
        Log::info('PAYMENT REQUEST', [
            'user_id'        => $user->id,
            'pembayaran_id'  => $pembayaran->id,
            'nama_pasien'    => optional($pembayaran->pendaftaran)->nama_pasien,
            'total_biaya'    => $pembayaran->total_biaya,
        ]);

        /**
         * =========================================
         * 4. BUAT TRANSAKSI MIDTRANS
         * =========================================
         */
        try {

            $result = $paymentService->createTransaction($pembayaran);

        } catch (\Exception $e) {

            // 🔥 tampilkan error asli (JANGAN DISEMBUNYIKAN DULU)
            dd('MIDTRANS ERROR: ' . $e->getMessage());
        }

        /**
         * =========================================
         * 5. SIMPAN PAYMENT REF (ANTI OVERWRITE)
         * =========================================
         */
        if (!$pembayaran->payment_ref) {
            $pembayaran->payment_ref = $result['order_id'];
            $pembayaran->save();
        }

        /**
         * =========================================
         * 6. TAMPILKAN HALAMAN SNAP
         * =========================================
         */
        return view('payment.pay', [
            'snapToken' => $result['snap_token'],
            'pembayaran'=> $pembayaran
        ]);
    }
}