<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Pembayaran;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\DB;

class CallbackController extends Controller
{
    public function handle(Request $request)
    {
        try {

            // =========================
            // 1. AMBIL DATA
            // =========================
            $data = $request->all();

            Log::info('📥 CALLBACK MASUK', $data);

            // =========================
            // 2. VALIDASI SIGNATURE
            // =========================
            $serverKey = env('MIDTRANS_SERVER_KEY');

            $localSignature = hash(
                "sha512",
                $data['order_id'] .
                $data['status_code'] .
                $data['gross_amount'] .
                $serverKey
            );

            if ($localSignature !== ($data['signature_key'] ?? '')) {
                Log::warning('❌ SIGNATURE INVALID', [
                    'order_id' => $data['order_id']
                ]);

                return response()->json(['message' => 'OK'], 200);
            }

            // =========================
            // 3. CARI DATA
            // =========================
            $pembayaran = Pembayaran::where('payment_ref', $data['order_id'])->first();

            if (!$pembayaran) {
                Log::warning('❌ DATA TIDAK DITEMUKAN', [
                    'order_id' => $data['order_id']
                ]);

                return response()->json(['message' => 'OK'], 200);
            }

            // =========================
            // 4. UPDATE STATUS
            // =========================
            DB::beginTransaction();

            $status = strtolower($data['transaction_status'] ?? '');

            if (in_array($status, ['capture', 'settlement'])) {
                $pembayaran->status = 'lunas';
                $pembayaran->tanggal_bayar = now();
            } elseif ($status === 'pending') {
                $pembayaran->status = 'belum_lunas';
            } elseif (in_array($status, ['deny', 'expire', 'cancel'])) {
                $pembayaran->status = 'gagal';
            }

            $pembayaran->paid_by = $data['payment_type'] ?? '-';
            $pembayaran->save();

            DB::commit();

            Log::info('✅ PEMBAYARAN DIUPDATE', [
                'order_id' => $data['order_id'],
                'status'   => $pembayaran->status
            ]);

            return response()->json(['message' => 'OK'], 200);

        } catch (\Exception $e) {

            DB::rollBack();

            Log::error('🔥 CALLBACK ERROR TOTAL: ' . $e->getMessage());

            return response()->json(['message' => 'OK'], 200);
        }
    }
}