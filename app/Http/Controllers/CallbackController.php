<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Pembayaran;
use Midtrans\Config;
use Midtrans\Notification;
use Illuminate\Support\Facades\Log;

class CallbackController extends Controller
{
    public function handle(Request $request)
    {
        // 1. Konfigurasi Midtrans
        Config::$serverKey = env('MIDTRANS_SERVER_KEY');
        Config::$isProduction = env('MIDTRANS_IS_PRODUCTION', false);
        Config::$isSanitized = true;
        Config::$is3ds = true;

        try {
            $notif = new Notification();
        } catch (\Exception $e) {
            Log::error('Midtrans Notification Error: ' . $e->getMessage());
            return response()->json(['message' => 'Invalid Notification'], 400);
        }

        $transactionStatus = $notif->transaction_status;
        $paymentType = $notif->payment_type;
        $orderId = $notif->order_id; // Ini adalah payment_ref kamu
        $fraudStatus = $notif->fraud_status;

        // 2. Cari data pembayaran berdasarkan payment_ref
        $pembayaran = Pembayaran::where('payment_ref', $orderId)->first();

        if (!$pembayaran) {
            Log::warning("Pembayaran dengan Ref: $orderId tidak ditemukan di database.");
            return response()->json(['message' => 'Data tidak ditemukan'], 404);
        }

        // 3. Logika Perubahan Status
        // Kita gunakan percabangan yang lebih lengkap sesuai dokumentasi Midtrans
        if ($transactionStatus == 'capture') {
            if ($fraudStatus == 'challenge') {
                $pembayaran->status = 'pending';
            } else if ($fraudStatus == 'accept') {
                $pembayaran->status = 'lunas';
                $pembayaran->tanggal_bayar = now();
            }
        } else if ($transactionStatus == 'settlement') {
            $pembayaran->status = 'lunas';
            $pembayaran->tanggal_bayar = now();
        } else if ($transactionStatus == 'pending') {
            $pembayaran->status = 'belum_lunas';
        } else if (in_array($transactionStatus, ['deny', 'expire', 'cancel'])) {
            $pembayaran->status = 'gagal';
        }

        // Simpan tipe pembayaran (opsional tapi bagus untuk laporan)
        $pembayaran->paid_by = $paymentType;
        
        if ($pembayaran->save()) {
            Log::info("Status Pembayaran Ref: $orderId berhasil diupdate menjadi: " . $pembayaran->status);
            return response()->json(['message' => 'Notification Handled']);
        }

        return response()->json(['message' => 'Failed to update'], 500);
    }
}