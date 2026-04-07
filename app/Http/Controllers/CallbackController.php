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
        DB::beginTransaction();

        try {

            // =========================
            // 1. AMBIL DATA
            // =========================
            $data = $request->all();

            Log::info('📥 CALLBACK MASUK', $data);

            // =========================
            // 2. VALIDASI SIGNATURE (FIX FORMAT)
            // =========================
            $serverKey = env('MIDTRANS_SERVER_KEY');

            $orderId      = $data['order_id'] ?? '';
            $statusCode   = $data['status_code'] ?? '';
            $grossAmount  = $data['gross_amount'] ?? '';
            $signatureKey = $data['signature_key'] ?? '';

            $localSignature = hash(
                "sha512",
                $orderId . $statusCode . $grossAmount . $serverKey
            );

            if ($localSignature !== $signatureKey) {
                Log::warning('❌ SIGNATURE INVALID', [
                    'order_id' => $orderId
                ]);

                DB::commit();
                return response()->json(['message' => 'OK'], 200);
            }

            // =========================
            // 3. CARI DATA
            // =========================
            $pembayaran = Pembayaran::where('payment_ref', $orderId)->first();

            if (!$pembayaran) {
                Log::warning('❌ DATA TIDAK DITEMUKAN', [
                    'order_id' => $orderId
                ]);

                DB::commit();
                return response()->json(['message' => 'OK'], 200);
            }

            // =========================
            // 4. NORMALISASI STATUS (FIX UTAMA)
            // =========================
            $status = strtolower(trim($data['transaction_status'] ?? ''));

            Log::info('🔥 STATUS MIDTRANS', [
                'raw' => $data['transaction_status'] ?? null,
                'clean' => $status
            ]);

            // =========================
            // 5. UPDATE STATUS
            // =========================
            switch ($status) {
                case 'capture':
                case 'settlement':
                    $pembayaran->status = 'lunas';
                    $pembayaran->tanggal_bayar = now();
                    break;

                case 'pending':
                    $pembayaran->status = 'belum_lunas';
                    break;

                case 'deny':
                case 'expire':
                case 'cancel':
                    $pembayaran->status = 'gagal';
                    break;

                default:
                    Log::warning('⚠️ STATUS TIDAK DIKENAL', [
                        'status' => $status
                    ]);
                    break;
            }

            // =========================
            // 6. SIMPAN DATA
            // =========================
            $pembayaran->paid_by = $data['payment_type'] ?? '-';
            $pembayaran->save();

            DB::commit();

            Log::info('✅ PEMBAYARAN DIUPDATE', [
                'order_id' => $orderId,
                'status'   => $pembayaran->status
            ]);

            return response()->json(['message' => 'OK'], 200);

        } catch (\Exception $e) {

            DB::rollBack();

            Log::error('🔥 CALLBACK ERROR TOTAL', [
                'message' => $e->getMessage(),
                'data' => $request->all()
            ]);

            return response()->json(['message' => 'OK'], 200);
        }
    }
}