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
        // 1. Cek Login
        $user = Auth::user();
        if (!$user) {
            return redirect()->route('login')->with('error', 'Silakan login terlebih dahulu.');
        }

        // 2. Ambil Data (Eager Load pendaftaran agar nama pasien muncul)
        $pembayaran = Pembayaran::with('pendaftaran')->findOrFail($id);

        // 3. Jika sudah lunas, stop.
        if ($pembayaran->status === 'lunas') {
            return redirect()->route('dashboard')->with('success', 'Pembayaran sudah lunas.');
        }

        /**
         * =============================================================
         * 4. LOGIKA PERBAIKAN SINKRONISASI ID
         * =============================================================
         */
        
        // JIKA sudah ada token tapi payment_ref masih kosong atau beda, 
        // kita PAKSA buat baru agar database dan Midtrans SINKRON.
        if (!$pembayaran->snap_token || !$pembayaran->payment_ref) {
            try {
                // Buat transaksi baru di Midtrans
                $result = $paymentService->createTransaction($pembayaran);

                // UPDATE DATABASE: Simpan snap_token DAN payment_ref sekaligus!
                $pembayaran->update([
                    'payment_ref' => $result['order_id'], // Ini yang bikin sinkron
                    'snap_token'  => $result['snap_token'],
                ]);

                Log::info('✅ ID Berhasil Disinkronkan', [
                    'db_id' => $pembayaran->id,
                    'midtrans_id' => $result['order_id']
                ]);

            } catch (\Exception $e) {
                Log::error('❌ Gagal Sinkron Midtrans: ' . $e->getMessage());
                
                // Jika error "already taken", hapus kolom agar user bisa refresh & generate ulang
                if (str_contains($e->getMessage(), 'already been taken')) {
                    $pembayaran->update(['snap_token' => null, 'payment_ref' => null]);
                }
                
                return redirect()->back()->with('error', 'Gagal memproses kode pembayaran. Silakan refresh halaman.');
            }
        }

        // 5. Tampilkan View dengan data yang sudah PASTI SINKRON
        return view('payment.pay', [
            'snapToken'  => $pembayaran->snap_token,
            'pembayaran' => $pembayaran
        ]);
    }

    /**
     * Callback Otomatis (Handle Notification)
     */
    public function callback(Request $request)
    {
        $serverKey = env('MIDTRANS_SERVER_KEY');
        $hashed = hash("sha512", $request->order_id . $request->status_code . $request->gross_amount . $serverKey);

        if ($hashed == $request->signature_key) {
            if (in_array($request->transaction_status, ['capture', 'settlement'])) {
                
                // CARI BERDASARKAN payment_ref (Bukan ID asli)
                $pembayaran = Pembayaran::where('payment_ref', $request->order_id)->first();
                
                if ($pembayaran) {
                    $pembayaran->update([
                        'status' => 'lunas',
                        'tanggal_bayar' => now(),
                        'paid_by' => $request->payment_type
                    ]);
                    Log::info("🔥 OTOMATIS LUNAS: " . $request->order_id);
                }
            }
        }

        return response()->json(['status' => 'OK']);
    }
}