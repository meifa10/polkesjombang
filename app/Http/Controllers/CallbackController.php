<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Pembayaran;

class CallbackController extends Controller
{
    public function handle(Request $request)
    {
        $data = $request->all();

        // 🔥 DEBUG WAJIB
        file_put_contents(
            storage_path('logs/debug.txt'),
            json_encode($data) . PHP_EOL,
            FILE_APPEND
        );

        // =========================
        // CARI DATA
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

        if ($status == 'settlement' || $status == 'capture') {
            $pembayaran->status = 'lunas';
            $pembayaran->tanggal_bayar = now();
        } elseif ($status == 'pending') {
            $pembayaran->status = 'belum_lunas';
        } else {
            $pembayaran->status = 'gagal';
        }

        $pembayaran->paid_by = $data['payment_type'] ?? '-';
        $pembayaran->save();

        return response()->json(['message' => 'OK'], 200);
    }
}