<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Pembayaran;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Http;

class CallbackController extends Controller
{
    public function handle(Request $request)
    {
        try {

            /**
             * =========================
             * 1. AMBIL DATA
             * =========================
             */
            $data = $request->all();

            Log::info('📥 CALLBACK MASUK', $data);

            /**
             * =========================
             * 2. VALIDASI SIGNATURE (AMAN)
             * =========================
             */
            $serverKey = env('MIDTRANS_SERVER_KEY');

            $grossAmount = number_format((float)$data['gross_amount'], 2, '.', '');

            $localSignature = hash(
                "sha512",
                $data['order_id'] .
                $data['status_code'] .
                $grossAmount .
                $serverKey
            );

            if ($localSignature !== ($data['signature_key'] ?? '')) {
                Log::warning('❌ SIGNATURE INVALID', [
                    'order_id' => $data['order_id']
                ]);

                // 🔥 JANGAN 403 → tetap 200
                return response()->json(['message' => 'OK'], 200);
            }

            /**
             * =========================
             * 3. CARI DATA
             * =========================
             */
            $pembayaran = Pembayaran::where('payment_ref', $data['order_id'])->first();

            if (!$pembayaran) {
                Log::warning('❌ DATA TIDAK DITEMUKAN');

                return response()->json(['message' => 'OK'], 200);
            }

            /**
             * =========================
             * 4. UPDATE STATUS
             * =========================
             */
            DB::beginTransaction();

            $status = strtolower($data['transaction_status'] ?? '');

            if (in_array($status, ['capture', 'settlement'])) {
                $pembayaran->status = 'lunas';
                $pembayaran->tanggal_bayar = now();
            } elseif ($status == 'pending') {
                $pembayaran->status = 'belum_lunas';
            } elseif (in_array($status, ['deny', 'expire', 'cancel'])) {
                $pembayaran->status = 'gagal';
            }

            $pembayaran->paid_by = $data['payment_type'] ?? '-';
            $pembayaran->save();

            DB::commit();

            Log::info('✅ PEMBAYARAN DIUPDATE', [
                'order_id' => $data['order_id'],
                'status' => $pembayaran->status
            ]);

            /**
             * =========================
             * 5. SYNC KE INSTANSI (JANGAN GAGALIN CALLBACK)
             * =========================
             */
            try {
                Http::timeout(5)
                    ->withHeaders([
                        'X-API-KEY' => 'POLKES_SECRET'
                    ])
                    ->post('https://polkesinstansi.satcloud.tech/api/update-status', [
                        'order_id' => $data['order_id'],
                        'status'   => $pembayaran->status
                    ]);

            } catch (\Exception $e) {
                Log::error('❌ GAGAL SYNC INSTANSI: ' . $e->getMessage());
            }

            return response()->json(['message' => 'OK'], 200);

        } catch (\Exception $e) {

            Log::error('🔥 CALLBACK ERROR TOTAL: ' . $e->getMessage());

            // 🔥 WAJIB RETURN 200 BIAR MIDTRANS GA RETRY
            return response()->json(['message' => 'OK'], 200);
        }
    }
}