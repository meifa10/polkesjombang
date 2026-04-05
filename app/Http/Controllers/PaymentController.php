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
        // Menggunakan findOrFail agar otomatis 404 jika ID tidak valid di URL
        $pembayaran = Pembayaran::with('pendaftaran')->findOrFail($id);

        // 3. Cek Status Pembayaran (Jika sudah lunas, jangan diproses lagi)
        if ($pembayaran->status === 'lunas') {
            return redirect()->route('dashboard')
                ->with('success', 'Pembayaran ini sudah lunas.');
        }

        /**
         * =============================================================
         * 4. LOGIKA RE-USE SNAP TOKEN (EFISIENSI)
         * =============================================================
         * Jika sudah ada snap_token DAN payment_ref di database, 
         * kita gunakan yang sudah ada agar tidak terus-menerus membuat 
         * transaksi baru di Midtrans untuk tagihan yang sama.
         */
        if ($pembayaran->snap_token && $pembayaran->payment_ref) {
            Log::info('Reusing existing Snap Token', [
                'pembayaran_id' => $pembayaran->id,
                'order_id' => $pembayaran->payment_ref
            ]);
            
            return view('payment.pay', [
                'snapToken'  => $pembayaran->snap_token,
                'pembayaran' => $pembayaran
            ]);
        }

        // 5. Buat Transaksi Baru ke Midtrans jika belum ada token
        try {
            /**
             * Memanggil service untuk generate Snap Token.
             * Service akan mengembalikan array ['snap_token' => ..., 'order_id' => ...]
             */
            $result = $paymentService->createTransaction($pembayaran);

            /**
             * =============================================================
             * 6. UPDATE DATABASE (BAGIAN PALING KRUSIAL)
             * =============================================================
             * Kita WAJIB menyimpan 'payment_ref' (Order ID dari Midtrans)
             * agar sama dengan yang tersimpan di server Midtrans.
             */
            $pembayaran->update([
                'payment_ref' => $result['order_id'], // HARUS DISIMPAN!
                'snap_token'  => $result['snap_token'],
            ]);

            Log::info('New Midtrans Transaction Created & Synced', [
                'pembayaran_id' => $pembayaran->id,
                'order_id'      => $result['order_id']
            ]);

            return view('payment.pay', [
                'snapToken'  => $result['snap_token'],
                'pembayaran' => $pembayaran
            ]);

        } catch (\Exception $e) {
            Log::error('MIDTRANS CRITICAL ERROR: ' . $e->getMessage(), [
                'pembayaran_id' => $pembayaran->id
            ]);

            // Jika error disebabkan 'order_id taken', kita paksa reset kolom agar bisa generate ulang
            if (str_contains($e->getMessage(), 'already been taken')) {
                $pembayaran->update(['payment_ref' => null, 'snap_token' => null]);
                return redirect()->route('payment.pay', $id)->with('error', 'Menyinkronkan ulang koneksi... Silakan klik bayar kembali.');
            }

            return redirect()->route('dashboard')
                ->with('error', 'Gagal terhubung ke server pembayaran: ' . $e->getMessage());
        }
    }

    /**
     * Callback dari Midtrans (Notification)
     * Tambahkan route ini di VerifyCsrfToken agar tidak Error 419
     */
    public function callback(Request $request, PaymentService $paymentService)
    {
        Log::info('Midtrans Notification Received', $request->all());
        
        try {
            // Gunakan service untuk menangani logika update status otomatis
            $status = $paymentService->handleNotification($request->all());
            
            return response()->json([
                'status' => 'success',
                'message' => 'Notification processed'
            ], 200);

        } catch (\Exception $e) {
            Log::error('Callback Processing Error: ' . $e->getMessage());
            return response()->json([
                'status' => 'error',
                'message' => $e->getMessage()
            ], 500);
        }
    }
}