<?php

namespace App\Http\Controllers;

use App\Models\Pembayaran;
use App\Services\PaymentService;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Illuminate\Http\Request;

class PaymentController extends Controller
{
    public function pay($id, PaymentService $paymentService)
    {
        /**
         * =========================
         * 1. VALIDASI LOGIN
         * =========================
         */
        if (!Auth::check()) {
            return redirect()->route('login')
                ->with('error', 'Silakan login terlebih dahulu.');
        }

        /**
         * =========================
         * 2. AMBIL DATA
         * =========================
         */
        $pembayaran = Pembayaran::with('pendaftaran')->find($id);

        if (!$pembayaran) {
            return redirect()->route('dashboard')
                ->with('error', 'Data pembayaran tidak ditemukan.');
        }

        if (!$pembayaran) {
            return redirect()->route('dashboard')
                ->with('error', 'Data pembayaran tidak ditemukan.');
        }

        /**
         * =========================
         * 3. CEK STATUS
         * =========================
         */
        if (strtolower($pembayaran->status) === 'lunas') {
            return redirect()->route('dashboard')
                ->with('success', 'Pembayaran sudah lunas.');
        }

        /**
         * =========================
         * 4. GENERATE SNAP TOKEN
         * =========================
         */
        if (empty($pembayaran->snap_token)) {
            try {
                Log::info('🔄 GENERATE SNAP TOKEN...', [
                    'pembayaran_id' => $pembayaran->id
                ]);

                // ✅ HANYA PANGGIL SERVICE (JANGAN UPDATE LAGI!)
                $paymentService->createTransaction($pembayaran);

                // 🔥 WAJIB: REFRESH DATA DARI DATABASE
                $pembayaran->refresh();

                // 🔥 VALIDASI TOKEN
                if (empty($pembayaran->snap_token)) {
                    throw new \Exception("Snap token tetap kosong setelah generate");
                }

                Log::info('✅ SNAP TOKEN BERHASIL', [
                    'order_id' => $pembayaran->payment_ref,
                    'token' => $pembayaran->snap_token
                ]);

            } catch (\Exception $e) {

                Log::error('❌ MIDTRANS ERROR: ' . $e->getMessage());

                // reset supaya bisa retry
                $pembayaran->update([
                    'payment_ref' => null,
                    'snap_token'  => null
                ]);

                return redirect()->back()
                    ->with('error', 'Gagal membuat transaksi pembayaran. Silakan refresh.');
            }
        }

        /**
         * =========================
         * 5. DEBUG TOKEN
         * =========================
         */
        Log::info('📦 SNAP TOKEN DIKIRIM KE VIEW', [
            'token' => $pembayaran->snap_token
        ]);

        /**
         * =========================
         * 6. RETURN VIEW
         * =========================
         */
        return view('payment.pay', [
            'snapToken'  => $pembayaran->snap_token,
            'pembayaran' => $pembayaran
        ]);
    }

    /**
     * =========================
     * CALLBACK MIDTRANS
     * =========================
     */
    public function callback(Request $request)
    {
        $serverKey = env('MIDTRANS_SERVER_KEY');

        $hashed = hash(
            "sha512",
            $request->order_id .
            $request->status_code .
            $request->gross_amount .
            $serverKey
        );

        /**
         * =========================
         * VALIDASI SIGNATURE
         * =========================
         */
        if ($hashed !== $request->signature_key) {
            Log::error('❌ SIGNATURE INVALID', [
                'order_id' => $request->order_id
            ]);

            return response()->json(['status' => 'INVALID'], 403);
        }

        Log::info('🔥 CALLBACK MASUK', [
            'order_id' => $request->order_id,
            'status'   => $request->transaction_status
        ]);

        $pembayaran = Pembayaran::where('payment_ref', $request->order_id)->first();

        if (!$pembayaran) {
            Log::warning('❌ DATA TIDAK DITEMUKAN', [
                'order_id' => $request->order_id
            ]);

            return response()->json(['status' => 'NOT FOUND'], 200);
        }

        /**
         * =========================
         * UPDATE STATUS PEMBAYARAN
         * =========================
         */
        $status = $request->transaction_status;

        if (in_array($status, ['capture', 'settlement'])) {

            $pembayaran->update([
                'status' => 'lunas',
                'tanggal_bayar' => now(),
                'paid_by' => $request->payment_type
            ]);

            Log::info('✅ PEMBAYARAN LUNAS', [
                'order_id' => $request->order_id
            ]);

        } elseif ($status === 'pending') {

            $pembayaran->update([
                'status' => 'belum_lunas'
            ]);

        } elseif (in_array($status, ['deny', 'expire', 'cancel'])) {

            $pembayaran->update([
                'status' => 'gagal'
            ]);
        }

        return response()->json(['status' => 'OK']);
    }
}