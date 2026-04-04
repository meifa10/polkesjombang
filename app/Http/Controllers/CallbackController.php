<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Pembayaran;
use Midtrans\Config;
use Midtrans\Notification;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\DB;

class CallbackController extends Controller
{
    /**
     * Handle Midtrans Payment Notification (Webhook)
     */
    public function handle(Request $request)
    {
        // 1. Konfigurasi SDK Midtrans
        // Menggunakan env() secara dinamis agar aman saat pindah ke Production
        Config::$serverKey = env('MIDTRANS_SERVER_KEY');
        Config::$isProduction = env('MIDTRANS_IS_PRODUCTION', false);
        Config::$isSanitized = true;
        Config::$is3ds = true;

        try {
            // Notification() otomatis menangkap data POST dari Midtrans
            $notif = new Notification();
        } catch (\Exception $e) {
            Log::error('Midtrans Callback Error (Invalid Payload): ' . $e->getMessage());
            return response()->json(['message' => 'Invalid Notification Payload'], 400);
        }

        // Ambil data penting dari Midtrans
        $transactionStatus = $notif->transaction_status;
        $paymentType       = $notif->payment_type;
        $orderId           = $notif->order_id; // Ini harus sesuai dengan kolom payment_ref di DB
        $fraudStatus       = $notif->fraud_status;

        // 2. Cari data pembayaran berdasarkan payment_ref
        // Kita gunakan first() agar jika tidak ada, kita bisa handle errornya
        $pembayaran = Pembayaran::where('payment_ref', $orderId)->first();

        if (!$pembayaran) {
            Log::warning("Midtrans Callback Warning: Transaksi dengan Ref $orderId tidak ditemukan.");
            return response()->json(['message' => 'Transaction not found'], 404);
        }

        // Gunakan Database Transaction agar data aman
        DB::beginTransaction();

        try {
            // 3. Logika Update Status Berdasarkan Dokumentasi Midtrans
            if ($transactionStatus == 'capture') {
                if ($fraudStatus == 'challenge') {
                    // Pembayaran kartu kredit yang butuh review manual
                    $pembayaran->status = 'pending';
                } else if ($fraudStatus == 'accept') {
                    // Pembayaran kartu kredit sukses
                    $pembayaran->status = 'lunas';
                    $pembayaran->tanggal_bayar = now();
                }
            } else if ($transactionStatus == 'settlement') {
                // Pembayaran (Gopay, QRIS, Transfer Bank, Alfamart, dll) sukses
                $pembayaran->status = 'lunas';
                $pembayaran->tanggal_bayar = now();
            } else if ($transactionStatus == 'pending') {
                // User sudah pilih metode tapi belum bayar
                $pembayaran->status = 'belum_lunas';
            } else if (in_array($transactionStatus, ['deny', 'expire', 'cancel'])) {
                // Pembayaran gagal atau kedaluwarsa
                $pembayaran->status = 'gagal';
            }

            // Simpan detail tambahan
            $pembayaran->paid_by = $paymentType;
            $pembayaran->save();

            DB::commit();

            Log::info("Midtrans Callback Success: Ref $orderId updated to " . $pembayaran->status);
            
            return response()->json([
                'status' => 'success',
                'message' => 'Payment status updated successfully'
            ], 200);

        } catch (\Exception $e) {
            DB::rollBack();
            Log::error("Midtrans Callback Database Error: " . $e->getMessage());
            return response()->json(['message' => 'Server Error during update'], 500);
        }
    }
}