<?php

namespace App\Http\Controllers;

use App\Models\Pembayaran;
use App\Services\PaymentService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;

class PaymentController extends Controller
{
    /**
     * Menampilkan Halaman Pembayaran & Meminta Snap Token
     */
    public function pay($id, PaymentService $paymentService)
    {
        $user = Auth::user();

        // Cari data pembayaran yang belum lunas milik user yang sedang login
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
            Log::error('Gagal memproses pembayaran: ' . $e->getMessage());
            return back()->with('error', 'Gagal memproses pembayaran ke Midtrans.');
        }
    }

    /**
     * Menangani Callback / Webhook dari Midtrans
     * Ini yang bertugas mengubah status di DB jadi LUNAS secara otomatis
     */
    public function callback(Request $request)
    {
        $serverKey = config('services.midtrans.server_key');
        $hashed = hash("sha512", $request->order_id . $request->status_code . $request->gross_amount . $serverKey);

        // 1. Validasi Signature Key agar aman dari hacker
        if ($hashed !== $request->signature_key) {
            return response()->json(['message' => 'Invalid signature'], 403);
        }

        // 2. Cari data pembayaran berdasarkan payment_ref (order_id di Midtrans)
        $pembayaran = Pembayaran::where('payment_ref', $request->order_id)->first();

        if (!$pembayaran) {
            return response()->json(['message' => 'Data tidak ditemukan'], 404);
        }

        // 3. Logika Update Status
        $transactionStatus = $request->transaction_status;
        $type = $request->payment_type;

        if ($transactionStatus == 'settlement' || $transactionStatus == 'capture') {
            // ✅ PERBAIKAN: Gunakan tanda kutip untuk string agar tidak error SQL
            $pembayaran->update([
                'status'        => 'lunas',
                'tanggal_bayar' => now(),
                'paid_by'       => (string) $type, // Memastikan masuk sebagai teks
                'metode'        => 'transfer'
            ]);
        } elseif ($transactionStatus == 'pending') {
            $pembayaran->update(['status' => 'belum_lunas']);
        } elseif (in_array($transactionStatus, ['deny', 'expire', 'cancel'])) {
            $pembayaran->update(['status' => 'gagal']);
        }

        return response()->json(['message' => 'Callback diproses']);
    }
}