<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use App\Models\Pembayaran;
use App\Models\PendaftaranPoli;
use App\Services\PaymentService;

class PaymentController extends Controller
{
    public function pay($id, PaymentService $paymentService)
    {
        if (!Auth::check()) {
            return redirect('/login');
        }

        $user = Auth::user();
        $pembayaran = Pembayaran::with('pendaftaran')
            ->where('id', $id)
            ->whereHas('pendaftaran', function ($q) use ($user) {
                $q->where('nama_pasien', $user->name);
            })->first();

        if (!$pembayaran) {
            return redirect('/dashboard')->with('error', 'Pembayaran tidak ditemukan');
        }

        if ($pembayaran->status == 'lunas') {
            return redirect('/dashboard')->with('success', 'Pembayaran sudah lunas');
        }

        try {
            $result = $paymentService->createTransaction($pembayaran);
            session(['last_order_id' => $pembayaran->payment_ref]);

            return view('payment.pay', [
                'snapToken' => $result['snap_token'],
                'pembayaran' => $pembayaran
            ]);
        } catch (\Exception $e) {
            Log::error('Midtrans Error: ' . $e->getMessage());
            return redirect('/dashboard')->with('error', 'Gagal memproses pembayaran');
        }
    }

    public function callback(Request $request)
    {
        $orderId = $request->order_id;
        $transactionStatus = $request->transaction_status;
        $paymentType = $request->payment_type ?? 'midtrans';

        $pembayaran = Pembayaran::where('payment_ref', $orderId)->first();

        if (!$pembayaran) {
            return response()->json(['message' => 'Not Found'], 404);
        }

        DB::beginTransaction();
        try {
            if (in_array($transactionStatus, ['capture', 'settlement'])) {
                // Update Tabel Pembayaran
                $pembayaran->update([
                    'status' => 'lunas',
                    'paid_by' => $paymentType,
                    'tanggal_bayar' => now(),
                    'metode' => 'midtrans'
                ]);

                // Update Tabel Pendaftaran (Aktivitas Terakhir)
                PendaftaranPoli::where('id', $pembayaran->pendaftaran_id)
                    ->update(['status' => 'selesai']);

                Log::info("Payment Success for Order ID: $orderId");
            } elseif ($transactionStatus == 'pending') {
                $pembayaran->update(['status' => 'pending']);
            } elseif (in_array($transactionStatus, ['deny', 'cancel', 'expire'])) {
                $pembayaran->update(['status' => 'gagal']);
            }

            DB::commit();
            return response()->json(['message' => 'OK']);
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Callback Error: ' . $e->getMessage());
            return response()->json(['message' => 'ERROR'], 500);
        }
    }

    public function finish(Request $request)
    {
        $orderId = $request->order_id ?? $request->query('order_id') ?? session('last_order_id');

        if (!$orderId) {
            return redirect('/dashboard')->with('error', 'Order ID tidak ditemukan');
        }

        $pembayaran = Pembayaran::where('payment_ref', $orderId)->first();

        if ($pembayaran) {
            // Kita lakukan update lagi di sini untuk jaga-jaga jika callback delay
            DB::transaction(function () use ($pembayaran) {
                $pembayaran->update([
                    'status' => 'lunas',
                    'paid_by' => 'midtrans',
                    'tanggal_bayar' => now(),
                    'metode' => 'midtrans'
                ]);

                PendaftaranPoli::where('id', $pembayaran->pendaftaran_id)
                    ->update(['status' => 'selesai']);
            });

            return redirect('/dashboard')->with('success', 'Pembayaran berhasil dikonfirmasi');
        }

        return redirect('/dashboard');
    }

    /**
     * Menampilkan halaman cetak struk pembayaran.
     */
    public function cetakStruk($id)
    {
        // Ambil data pembayaran berdasarkan ID beserta data pendaftarannya
        $pembayaran = Pembayaran::with('pendaftaran')->findOrFail($id);

        // Pastikan hanya yang sudah lunas yang bisa dicetak
        if ($pembayaran->status != 'lunas') {
            abort(403, 'Struk hanya dapat dicetak untuk pembayaran yang sudah lunas.');
        }

        // ✅ UBAH DI SINI: Ganti 'pembayaran.struk' menjadi 'payment.struk'
        return view('payment.struk', compact('pembayaran'));
    }
}