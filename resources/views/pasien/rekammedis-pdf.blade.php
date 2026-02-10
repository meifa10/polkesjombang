<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <style>
        body {
            font-family: DejaVu Sans;
            font-size: 12px;
            color: #111827;
        }
        h2 {
            margin-bottom: 5px;
        }
        hr {
            margin: 15px 0;
        }
        .box {
            margin-bottom: 15px;
            padding: 10px;
            border: 1px solid #e5e7eb;
            border-radius: 6px;
        }
        .label {
            font-weight: bold;
        }
    </style>
</head>
<body>

    <h2>Rekam Medis Pasien</h2>

    <p><span class="label">Nama:</span> {{ $pendaftaran->nama_pasien }}</p>
    <p><span class="label">Poli:</span> {{ $pendaftaran->poli }}</p>
    <p><span class="label">Tanggal Cetak:</span>
        {{ now()->translatedFormat('d F Y') }}
    </p>

    <hr>

    @if($rekamMedis->count() === 0)
        <p>Tidak ada data rekam medis.</p>
    @else
        @foreach($rekamMedis as $rm)
            <div class="box">
                <p>
                    <strong>
                        {{ \Carbon\Carbon::parse($rm->created_at)
                            ->translatedFormat('d F Y') }}
                    </strong>
                </p>
                <p><span class="label">Keluhan:</span> {{ $rm->keluhan }}</p>
                <p><span class="label">Diagnosis:</span> {{ $rm->diagnosis }}</p>
                <p><span class="label">Tindakan:</span> {{ $rm->tindakan }}</p>
                <p><span class="label">Resep:</span> {{ $rm->resep }}</p>
            </div>
        @endforeach
    @endif

    @if(isset($pembayaran))
        <hr>
        <h3>Informasi Pembayaran</h3>
        <p>
            <span class="label">Total Biaya:</span>
            Rp {{ number_format($pembayaran->total_biaya) }}
        </p>
        <p>
            <span class="label">Status:</span>
            {{ strtoupper(str_replace('_',' ', $pembayaran->status)) }}
        </p>
        <p>
            <span class="label">Metode:</span>
            {{ strtoupper($pembayaran->metode) }}
        </p>
    @endif

</body>
</html>
