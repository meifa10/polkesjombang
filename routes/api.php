<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Models\Pembayaran;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
| Route ini otomatis pakai prefix: /api
| Jadi aksesnya nanti: /api/update-status
|--------------------------------------------------------------------------
*/

Route::post('/update-status', function (Request $request) {

    // 🔐 (Optional) Security API Key
    if ($request->header('X-API-KEY') !== 'POLKES_SECRET') {
        return response()->json([
            'message' => 'Unauthorized'
        ], 403);
    }

    // 🔍 Validasi input
    $request->validate([
        'order_id' => 'required',
        'status' => 'required'
    ]);

    // 🔎 Cari data pembayaran
    $pembayaran = Pembayaran::where('payment_ref', $request->order_id)->first();

    if (!$pembayaran) {
        return response()->json([
            'message' => 'Data tidak ditemukan'
        ], 404);
    }

    // ✅ Update status
    $pembayaran->status = $request->status;
    $pembayaran->save();

    return response()->json([
        'message' => 'Status berhasil diupdate',
        'status' => $pembayaran->status
    ]);
});