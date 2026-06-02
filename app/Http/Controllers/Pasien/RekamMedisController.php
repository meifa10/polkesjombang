<?php

namespace App\Http\Controllers\Pasien;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Barryvdh\DomPDF\Facade\Pdf;
use Carbon\Carbon;

class RekamMedisController extends Controller
{
    public function index(Request $request)
    {
        $user = Auth::user();

        if (!$user) {
            return redirect()->route('login');
        }

        $querySearch = $request->input('q');
        $dateFrom = $request->input('from');
        $dateTo = $request->input('to');

        $rekamMedisRaw = DB::table('rekam_medis')
            ->join('pendaftaran_poli', 'rekam_medis.pendaftaran_id', '=', 'pendaftaran_poli.id')
            ->leftJoin('users as dokter', 'pendaftaran_poli.dokter_id', '=', 'dokter.id')
            ->leftJoin('pembayaran', 'pendaftaran_poli.id', '=', 'pembayaran.pendaftaran_id')
            ->where('pendaftaran_poli.nama_pasien', $user->name)
            ->where('pembayaran.status', 'lunas')
            ->when($querySearch, function ($query, $querySearch) {
                return $query->where(function ($q) use ($querySearch) {
                    $q->where('rekam_medis.diagnosis', 'like', '%' . $querySearch . '%')
                        ->orWhere('rekam_medis.resep', 'like', '%' . $querySearch . '%')
                        ->orWhere('rekam_medis.keluhan', 'like', '%' . $querySearch . '%');
                });
            })
            ->when($dateFrom, function ($query, $dateFrom) {
                return $query->whereDate('rekam_medis.created_at', '>=', $dateFrom);
            })
            ->when($dateTo, function ($query, $dateTo) {
                return $query->whereDate('rekam_medis.created_at', '<=', $dateTo);
            })
            ->select(
                'rekam_medis.*',
                'pendaftaran_poli.poli',
                'dokter.name as nama_dokter',
                'pembayaran.total_biaya',
                'pembayaran.biaya_dokter',
                'pembayaran.total_obat',
                'pembayaran.biaya_admin',
                'pembayaran.status as status_bayar'
            )
            ->orderByDesc('rekam_medis.created_at')
            ->get();

        $rekamMedis = $rekamMedisRaw->map(function ($item) {
            $item->rincian_obat = $this->parseResepObat(
                $item->resep,
                $item->total_obat
            );

            return $item;
        });

        $rekamMedisGrouped = $rekamMedis->groupBy(function ($item) {
            return Carbon::parse($item->created_at)
                ->locale('id')
                ->isoFormat('MMMM YYYY');
        });

        $pendaftaran = DB::table('pendaftaran_poli')
            ->where('nama_pasien', $user->name)
            ->orderByDesc('created_at')
            ->first();

        return view(
            'pasien.rekammedis',
            compact(
                'pendaftaran',
                'rekamMedisGrouped'
            )
        );
    }

    public function pdf($id)
    {
        $user = Auth::user();

        $rmSingle = DB::table('rekam_medis')
            ->where('id', $id)
            ->first();

        if (!$rmSingle) {
            abort(404);
        }

        $pendaftaran = DB::table('pendaftaran_poli')
            ->leftJoin('users as dokter', 'pendaftaran_poli.dokter_id', '=', 'dokter.id')
            ->where('pendaftaran_poli.id', $rmSingle->pendaftaran_id)
            ->where('pendaftaran_poli.nama_pasien', $user->name)
            ->select(
                'pendaftaran_poli.*',
                'dokter.name as nama_dokter'
            )
            ->first();

        if (!$pendaftaran) {
            abort(404);
        }

        $pembayaran = DB::table('pembayaran')
            ->where('pendaftaran_id', $pendaftaran->id)
            ->first();

        if (!$pembayaran || strtolower($pembayaran->status) !== 'lunas') {
            return redirect()->back()->with(
                'error',
                'Rekam medis belum dapat diunduh karena tagihan belum dilunasi.'
            );
        }

        $rekamMedis = DB::table('rekam_medis')
            ->where('pendaftaran_id', $pendaftaran->id)
            ->get();

        $pendaftaran->dokter = (object) [
            'name' => $pendaftaran->nama_dokter
        ];

        $rincianObat = $this->parseResepObat(
            $rmSingle->resep,
            $pembayaran->total_obat
        );

        $pdf = Pdf::loadView(
            'pasien.rekammedis-pdf',
            compact(
                'pendaftaran',
                'rekamMedis',
                'pembayaran',
                'rincianObat'
            )
        );

        return $pdf->download(
            'rekam-medis-' . time() . '.pdf'
        );
    }

    private function parseResepObat($resepText, $totalObatNominal)
    {
        if (empty($resepText)) {
            return [];
        }

        $lines = preg_split('/[,\n\r]+/', $resepText);

        $result = [];

        foreach ($lines as $line) {
            $line = trim($line);

            if (empty($line)) {
                continue;
            }

            $namaObat = $line;
            $qty = 5;

            if (strpos($line, '-') !== false) {
                $parts = explode('-', $line);
                $namaObat = trim($parts[0]);
            }

            $result[] = [
                'nama' => $namaObat,
                'qty' => $qty,
                'harga' => 0,
                'total' => 0
            ];
        }

        $totalItems = count($result);

        if ($totalItems > 0 && $totalObatNominal > 0) {
            $biayaPerItem = $totalObatNominal / $totalItems;

            foreach ($result as &$res) {
                $res['total'] = $biayaPerItem;
                $res['harga'] = $biayaPerItem / $res['qty'];
            }
        }

        return $result;
    }
}