<?php

namespace App\Http\Controllers;

use App\Models\Pembayaran;
use App\Services\PaymentService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\DB;

class PaymentController extends Controller
{
    /**
     * Menampilkan Halaman Pembayaran & Meminta Snap Token
     */
    public function pay($id, PaymentService $paymentService)
    {
        $user = Auth::user();

        // Cari data pembayaran yang belum lunas milik user yang sedang login
        // Pastikan relasi 'pendaftaran' sudah ada di Model Pembayaran
        $pembayaran = Pembayaran::where('id', $id)
            ->where('status', 'belum_lunas')
            ->whereHas('pendaftaran', function ($q) use ($user) {
                $q->where('nama_pasien', $user->name);
            })
            ->firstOrFail();

        try {
            // Memanggil service untuk mendapatkan snap_token
            $result = $paymentService->createTransaction($pembayaran);

            return view('payment.pay', [
                'snapToken' => $result['snap_token'],
                'pembayaran' => $pembayaran
            ]);
        } catch (\Exception $e) {
            Log::error('Midtrans Snap Error: ' . $e->getMessage());
            return back()->with('error', 'Gagal memproses ke sistem pembayaran. Silakan coba lagi.');
        }
    }

    /**
     * Menangani Callback / Webhook dari Midtrans
     * Endpoint ini harus didaftarkan di Dashboard Midtrans & dikecualikan dari CSRF
     */
    public function callback(Request $request)
    {
        // 1. Ambil data mentah dari request
        $data = $request->all();
        
        $orderId      = $data['order_id'] ?? null;
        $statusCode   = $data['status_code'] ?? null;
        $grossAmount  = $data['gross_amount'] ?? null;
        $signatureKey = $data['signature_key'] ?? null;
        $serverKey    = config('services.midtrans.server_key');

        // 2. Validasi Signature Key (Keamanan)
        $hashed = hash("sha512", $orderId . $statusCode . $grossAmount . $serverKey);
        if ($hashed !== $signatureKey) {
            Log::warning("Peringatan: Signature Key Tidak Valid untuk Order: " . $orderId);
            return response()->json(['message' => 'Invalid signature'], 403);
        }

        // 3. Cari data pembayaran di DB berdasarkan payment_ref
        $pembayaran = Pembayaran::where('payment_ref', $orderId)->first();

        if (!$pembayaran) {
            Log::error("Callback Error: Pembayaran tidak ditemukan untuk Ref: " . $orderId);
            return response()->json(['message' => 'Order not found'], 404);
        }

        // 4. Logika Update Status Berdasarkan Response Midtrans
        $transactionStatus = $data['transaction_status'];
        $type              = $data['payment_type'] ?? 'midtrans';

        try {
            // Gunakan Transaction DB untuk memastikan data konsisten
            DB::transaction(function () use ($pembayaran, $transactionStatus, $type) {
                
                if ($transactionStatus == 'settlement' || $transactionStatus == 'capture') {
                    
                    // UPDATE: Pastikan semua value dibungkus string '' agar tidak error SQL
                    $pembayaran->update([
                        'status'        => 'lunas',
                        'tanggal_bayar' => now(),
                        'paid_by'       => (string) $type, // Memaksa ke string
                        'metode'        => 'transfer',
                        'updated_at'    => now(),
                    ]);

                    Log::info("Pembayaran Berhasil diupdate: " . $pembayaran->payment_ref);

                } elseif ($transactionStatus == 'pending') {
                    $pembayaran->update(['status' => 'belum_lunas']);
                } elseif (in_array($transactionStatus, ['deny', 'expire', 'cancel'])) {
                    $pembayaran->update(['status' => 'gagal']);
                }
            });

            return response()->json(['message' => 'Callback success']);

        } catch (\Exception $e) {
            Log::error("Gagal Update Database saat Callback: " . $e->getMessage());
            return response()->json(['message' => 'Server Error saat update status'], 500);
        }
    }
}