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
        if (!Auth::check()) return redirect('/login');

        $user = Auth::user();
        $pembayaran = Pembayaran::with('pendaftaran')
            ->where('id', $id)
            ->whereHas('pendaftaran', function ($q) use ($user) {
                $q->where('nama_pasien', $user->name);
            })->first();

        if (!$pembayaran || $pembayaran->status == 'lunas') {
            return redirect('/dashboard')->with('error', 'Pembayaran tidak ditemukan/sudah lunas.');
        }

        // AMBIL DARI DATABASE PETUGAS
        $tarifDokter = DB::connection('mysql_petugas')->table('pengaturans')->where('key', 'tarif_dokter')->value('value') ?? 10000;
        $tarifAdmin = DB::connection('mysql_petugas')->table('pengaturans')->where('key', 'tarif_admin')->value('value') ?? 10000;
        $totalFix = $tarifDokter + $tarifAdmin + ($pembayaran->total_obat ?? 0);

        try {
            $result = $paymentService->createTransaction($pembayaran, $tarifDokter, $tarifAdmin, $totalFix);
            return view('payment.pay', compact('pembayaran', 'tarifDokter', 'tarifAdmin', 'totalFix', 'result'));
        } catch (\Exception $e) {
            Log::error('Midtrans Error: ' . $e->getMessage());
            return redirect('/dashboard')->with('error', 'Gagal memproses pembayaran');
        }
    }

    public function cetakStruk($id)
    {
        $pembayaran = Pembayaran::with(['pendaftaran.rekamMedis'])->findOrFail($id);
        if ($pembayaran->status != 'lunas') abort(403);

        // AMBIL DARI DATABASE PETUGAS
        $biayaDokter = DB::connection('mysql_petugas')->table('pengaturans')->where('key', 'tarif_dokter')->value('value') ?? $pembayaran->biaya_dokter;
        $biayaAdmin = DB::connection('mysql_petugas')->table('pengaturans')->where('key', 'tarif_admin')->value('value') ?? $pembayaran->biaya_admin;

        $resepString = $pembayaran->pendaftaran->rekamMedis->resep ?? '';
        $rincianObat = $this->parseResepPecahDetail($resepString, (int)($pembayaran->total_obat ?? 0));
        
        return view('payment.struk', compact('pembayaran', 'rincianObat', 'biayaDokter', 'biayaAdmin'));
    }

    private function parseResepPecahDetail($resepString, $totalHargaObat)
    {
        $listObat = [];
        if (empty(trim($resepString))) return $listObat;
        $rows = array_filter(preg_split('/[\n,]+/', $resepString));
        $count = count($rows);
        foreach ($rows as $row) {
            $listObat[] = ['nama' => trim($row), 'qty' => 1, 'harga' => $totalHargaObat/$count, 'total' => $totalHargaObat/$count];
        }
        return $listObat;
    }
}