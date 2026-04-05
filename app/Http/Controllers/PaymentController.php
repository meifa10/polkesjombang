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
         * 1. AMBIL USER LOGIN
         * =========================================
         */
        $user = Auth::user();

        /**
         * =========================================
         * 2. AMBIL DATA PEMBAYARAN + RELASI (WAJIB)
         * =========================================
         */
        $pembayaran = Pembayaran::with('pendaftaran')
            ->where('id', $id)
            ->where('status', 'belum_lunas')
            // ->whereHas('pendaftaran', function ($q) use ($user) {
            //     $q->where('nama_pasien', $user->name);
            // })
            ->firstOrFail();

        /**
         * =========================================
         * 3. DEBUG (OPSIONAL - BOLEH DIHAPUS NANTI)
         * =========================================
         */
        Log::info('PAYMENT DIPANGGIL', [
            'pembayaran_id' => $pembayaran->id,
            'pasien'        => optional($pembayaran->pendaftaran)->nama_pasien,
        ]);

        /**
         * =========================================
         * 4. BUAT TRANSAKSI MIDTRANS
         * =========================================
         */
        try {

            $result = $paymentService->createTransaction($pembayaran);

        } catch (\Exception $e) {

            Log::error('GAGAL BUAT TRANSAKSI MIDTRANS: ' . $e->getMessage());

            return back()->with('error', 'Gagal memproses pembayaran. Silakan coba lagi.');
        }

        /**
         * =========================================
         * 5. SIMPAN PAYMENT REF (JAGA-JAGA)
         * =========================================
         */
        if (!$pembayaran->payment_ref) {
            $pembayaran->payment_ref = $result['order_id'];
            $pembayaran->save();
        }

        /**
         * =========================================
         * 6. TAMPILKAN HALAMAN PEMBAYARAN
         * =========================================
         */
        return view('payment.pay', [
            'snapToken' => $result['snap_token'],
            'pembayaran'=> $pembayaran
        ]);
    }
}