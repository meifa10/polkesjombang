<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Pembayaran;

class CallbackController extends Controller
{
    public function handle(Request $request)
    {
        try {

            $data = $request->all();

            // 🔥 DEBUG MASUK
            file_put_contents(
                storage_path('logs/debug.txt'),
                json_encode($data) . PHP_EOL,
                FILE_APPEND
            );

            // =========================
            // VALIDASI DATA WAJIB
            // =========================
            if (!isset($data['order_id']) || !isset($data['transaction_status'])) {
                return response()->json(['message' => 'OK'], 200);
            }

            // =========================
            // CARI DATA PEMBAYARAN
            // =========================
            $pembayaran = Pembayaran::where('payment_ref', $data['order_id'])->first();

            if (!$pembayaran) {
                file_put_contents(
                    storage_path('logs/debug.txt'),
                    "DATA TIDAK DITEMUKAN: " . $data['order_id'] . PHP_EOL,
                    FILE_APPEND
                );

                return response()->json(['message' => 'OK'], 200);
            }

            // =========================
            // UPDATE STATUS
            // =========================
            $status = strtolower($data['transaction_status']);

            if (in_array($status, ['settlement', 'capture'])) {
                $pembayaran->status = 'lunas';
                $pembayaran->tanggal_bayar = now();
            } elseif ($status === 'pending') {
                $pembayaran->status = 'belum_lunas';
            } else {
                $pembayaran->status = 'gagal';
            }

            $pembayaran->paid_by = $data['payment_type'] ?? '-';

            $pembayaran->save();

            return response()->json(['message' => 'OK'], 200);

        } catch (\Exception $e) {

            // 🔥 SIMPAN ERROR ASLI
            file_put_contents(
                storage_path('logs/error.txt'),
                $e->getMessage() . PHP_EOL,
                FILE_APPEND
            );

            return response()->json(['message' => 'OK'], 200);
        }
    }
}