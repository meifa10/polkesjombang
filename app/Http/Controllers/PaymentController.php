<?php

namespace App\Http\Controllers;

use App\Models\Pembayaran;
use App\Services\PaymentService;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Illuminate\Http\Request;

class PaymentController extends Controller
{
    /**
     * =============================================================
     * HALAMAN PROSES PEMBAYARAN (MIDTRANS SNAP)
     * =============================================================
     */
    public function pay($id, PaymentService $paymentService)
    {
        // 1. Validasi Keamanan: Pastikan User Login
        $user = Auth::user();
        if (!$user) {
            return redirect()->route('login')
                ->with('error', 'Silakan login terlebih dahulu untuk melakukan pembayaran.');
        }

        // 2. Ambil Data Pembayaran dengan Eager Loading
        // Menggunakan findOrFail agar otomatis 404 jika ID tidak valid
        $pembayaran = Pembayaran::with('pendaftaran')->findOrFail($id);

        // 3. Cek Status Pembayaran
        if ($pembayaran->status === 'lunas') {
            return redirect()->route('dashboard')
                ->with('success', 'Pembayaran ini sudah lunas.');
        }

        /**
         * =============================================================
         * 4. LOGIKA SNAP TOKEN & ORDER ID (SOLUSI ERROR 400)
         * =============================================================
         * Jika sudah ada snap_token, kita coba pakai. 
         * Tapi jika Midtrans menolak karena order_id lama, 
         * kita akan buat baru di dalam blok try-catch.
         */
        
        // Jika token sudah ada di database, langsung tampilkan
        if ($pembayaran->snap_token) {
            Log::info('Reusing existing Snap Token', ['payment_id' => $pembayaran->id]);
            
            return view('payment.pay', [
                'snapToken'  => $pembayaran->snap_token,
                'pembayaran' => $pembayaran
            ]);
        }

        // 5. Buat Transaksi Baru ke Midtrans
        try {
            /**
             * Tips: Di dalam PaymentService->createTransaction(), 
             * pastikan Anda membuat order_id yang unik, misalnya:
             * 'PAY-' . $pembayaran->id . '-' . time()
             */
            $result = $paymentService->createTransaction($pembayaran);

            $pembayaran->update([
                'snap_token' => $result['snap_token'],
            ]);

            Log::info('New Midtrans Transaction Created', [
                'pembayaran_id' => $pembayaran->id,
                'order_id'      => $result['order_id']
            ]);

            return view('payment.pay', [
                'snapToken'  => $result['snap_token'],
                'pembayaran' => $pembayaran
            ]);

        } catch (\Exception $e) {
            // Log detail error untuk debugging
            Log::error('MIDTRANS CRITICAL ERROR: ' . $e->getMessage(), [
                'pembayaran_id' => $pembayaran->id,
                'trace'         => $e->getTraceAsString()
            ]);

            // Jika error disebabkan 'order_id taken', kita sarankan user refresh
            if (str_contains($e->getMessage(), 'already been taken')) {
                return redirect()->back()->with('error', 'Terjadi sinkronisasi ID. Silakan coba klik bayar sekali lagi.');
            }

            return redirect()->route('dashboard')
                ->with('error', 'Gagal terhubung ke server pembayaran (Midtrans).');
        }
    }

    /**
     * Optional: Tambahkan method callback untuk menerima notifikasi dari Midtrans
     */
    public function callback(Request $request, PaymentService $paymentService)
    {
        // Logika verifikasi signature dan update status pembayaran
        // Biasanya diproses di PaymentService agar controller tetap bersih
        return $paymentService->handleNotification($request->all());
    }
}