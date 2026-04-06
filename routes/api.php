<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Log;
use App\Models\Pembayaran;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
| Endpoint:
| POST /api/update-status
|--------------------------------------------------------------------------
*/

Route::post('/update-status', function (Request $request) {

    // ===============================
    // 1. LOG REQUEST MASUK
    // ===============================
    Log::info('📥 API UPDATE STATUS MASUK', [
        'headers' => $request->headers->all(),
        'body'    => $request->all()
    ]);

    // ===============================
    // 2. VALIDASI API KEY
    // ===============================
    $apiKey = $request->header('X-API-KEY');

    if ($apiKey !== 'POLKES_SECRET') {
        Log::warning('❌ API KEY SALAH', [
            'api_key' => $apiKey
        ]);

        return response()->json([
            'message' => 'Unauthorized'
        ], 403);
    }

    // ===============================
    // 3. VALIDASI INPUT
    // ===============================
    $validated = $request->validate([
        'order_id' => 'required|string',
        'status'   => 'required|string|in:lunas,belum_lunas,pending,gagal'
    ]);

    // ===============================
    // 4. CARI DATA PEMBAYARAN
    // ===============================
    $pembayaran = Pembayaran::where('payment_ref', $validated['order_id'])->first();

    if (!$pembayaran) {
        Log::warning('❌ DATA TIDAK DITEMUKAN', [
            'order_id' => $validated['order_id']
        ]);

        return response()->json([
            'message' => 'Data tidak ditemukan'
        ], 404);
    }

    // ===============================
    // 5. UPDATE STATUS
    // ===============================
    $pembayaran->status = $validated['status'];

    // kalau lunas, isi tanggal bayar
    if ($validated['status'] === 'lunas') {
        $pembayaran->tanggal_bayar = now();
    }

    $pembayaran->save();

    Log::info('✅ STATUS BERHASIL DIUPDATE', [
        'order_id' => $validated['order_id'],
        'status'   => $validated['status']
    ]);

    // ===============================
    // 6. RESPONSE
    // ===============================
    return response()->json([
        'message' => 'Status berhasil diupdate',
        'data' => [
            'order_id' => $pembayaran->payment_ref,
            'status'   => $pembayaran->status
        ]
    ], 200);

});