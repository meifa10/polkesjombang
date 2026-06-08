<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="utf-8">
    <title>Rekam Medis - {{ $pendaftaran->nama_pasien }}</title>
    <style>
        @page { margin: 1.5cm; }
        body { 
            font-family: 'Helvetica', 'Arial', sans-serif; 
            font-size: 11px; 
            color: #334155; 
            line-height: 1.5; 
            margin: 0;
            padding: 0;
        }

        .header-table { width: 100%; border-bottom: 2px solid #0f172a; padding-bottom: 10px; margin-bottom: 20px; }
        .hospital-name { font-size: 16px; font-weight: bold; color: #0f172a; text-transform: uppercase; letter-spacing: 0.5px; line-height: 1.3; }
        .hospital-info { font-size: 9px; color: #64748b; margin-top: 4px; }

        .patient-info-table { width: 100%; background-color: #f8fafc; padding: 12px; border-radius: 8px; margin-bottom: 25px; border: 1px solid #e2e8f0; }
        .info-label { color: #64748b; font-size: 8px; text-transform: uppercase; font-weight: bold; margin-bottom: 3px; }
        .info-value { font-size: 11px; font-weight: bold; color: #1e293b; }

        .section-title { font-size: 12px; font-weight: bold; color: #0f172a; margin-bottom: 12px; border-left: 4px solid #059669; padding-left: 10px; text-transform: uppercase; }
        .record-box { width: 100%; border: 1px solid #e2e8f0; border-radius: 8px; margin-bottom: 15px; border-collapse: collapse; overflow: hidden; }
        .record-header { background-color: #f1f5f9; padding: 8px 12px; font-weight: bold; font-size: 10px; border-bottom: 1px solid #e2e8f0; }
        .record-body { padding: 12px; }
        .prescription-area { margin-top: 10px; padding: 10px; background-color: #f0fdf4; border-left: 3px solid #059669; border-radius: 4px; }

        .billing-table { width: 100%; border-collapse: collapse; margin-top: 5px; }
        .billing-table td { padding: 9px 12px; border-bottom: 1px solid #f1f5f9; vertical-align: top; }
        .item-obat-detail { margin-top: 6px; padding-left: 8px; border-left: 2px dashed #cbd5e1; }
        .obat-row { width: 100%; margin-bottom: 4px; }
        .obat-subtext { font-size: 9px; color: #64748b; }
        .total-box { background-color: #0f172a; color: white; padding: 12px 15px; text-align: right; border-radius: 6px; }
        .footer { position: fixed; bottom: 0; width: 100%; text-align: center; font-size: 8px; color: #94a3b8; border-top: 1px solid #f1f5f9; padding-top: 8px; }
    </style>
</head>
<body>

    @php
        $biayaDokter = (int)($pembayaran->biaya_dokter ?? 0);
        $biayaAdmin = (int)($pembayaran->biaya_admin ?? 0);
        $biayaObat = (int)($pembayaran->total_obat ?? 0);
        $totalBersih = $biayaDokter + $biayaAdmin + $biayaObat;
    @endphp

    <table class="header-table">
        <tr>
            <td>
                <div class="hospital-name">Medical Center Digital<br>Polkes 05.09.15 Jombang</div>
                <div class="hospital-info">Jl. KH. Wahid Hasyim No.28 B Jombang, Jawa Timur<br>Telp / WA: 0877-7723-5386 | Sistem Rekam Medis Terintegrasi</div>
            </td>
            <td style="text-align: right; vertical-align: middle;">
                <div style="font-size: 13px; font-weight: bold; color: #059669; letter-spacing: 0.5px;">DOKUMEN REKAM MEDIS</div>
                <div style="font-size: 9px; color: #64748b; margin-top: 3px;">REG-ID: #{{ str_pad($pendaftaran->id, 6, '0', STR_PAD_LEFT) }}</div>
            </td>
        </tr>
    </table>

    <table class="patient-info-table" cellpadding="0" cellspacing="0">
        <tr>
            <td width="25%"><div class="info-label">Nama Pasien</div><div class="info-value">{{ strtoupper($pendaftaran->nama_pasien) }}</div></td>
            <td width="25%"><div class="info-label">Unit Layanan (Poli)</div><div class="info-value">{{ $pendaftaran->poli }}</div></td>
            <td width="25%"><div class="info-label">Dokter Pemeriksa</div><div class="info-value">{{ $pendaftaran->dokter->name ?? 'Belum Ditentukan' }}</div></td>
            <td width="25%"><div class="info-label">Waktu Cetak</div><div class="info-value">{{ now()->translatedFormat('d M Y, H:i') }} WIB</div></td>
        </tr>
    </table>

    <div class="section-title">Hasil Pemeriksaan Klinis</div>
    @forelse($rekamMedis as $rm)
        <div class="record-box">
            <div class="record-header">
                <table width="100%" cellpadding="0" cellspacing="0">
                    <tr>
                        <td>KUNJUNGAN TANGGAL: {{ \Carbon\Carbon::parse($rm->created_at)->translatedFormat('d F Y') }}</td>
                        <td style="text-align: right; font-weight: normal; font-size: 9px; color: #64748b;">DPJP: {{ $pendaftaran->dokter->name ?? '-' }}</td>
                    </tr>
                </table>
            </div>
            <div class="record-body">
                <table width="100%" cellpadding="0" cellspacing="0">
                    <tr><td width="110" style="padding-bottom: 6px;"><strong>Keluhan Pasien</strong></td><td style="padding-bottom: 6px;">: {{ $rm->keluhan }}</td></tr>
                    <tr><td style="padding-bottom: 6px;"><strong>Diagnosis Medis</strong></td><td style="padding-bottom: 6px;">: <strong>{{ $rm->diagnosis }}</strong></td></tr>
                    <tr><td style="padding-bottom: 6px;"><strong>Tindakan Dokter</strong></td><td style="padding-bottom: 6px;">: {{ $rm->tindakan }}</td></tr>
                </table>
                <div class="prescription-area">
                    <div class="info-label" style="color: #059669; margin-bottom: 3px;">Instruksi Resep / Terapi Obat:</div>
                    <div style="font-style: italic; color: #1e293b; font-size: 10px;">{{ $rm->resep != '-' ? $rm->resep : 'Tidak ada resep obat untuk kunjungan ini.' }}</div>
                </div>
            </div>
        </div>
    @empty
        <p style="text-align: center; color: #94a3b8; padding: 20px;">Data rekam medis klinis tidak ditemukan.</p>
    @endforelse

    @if(isset($pembayaran))
    <div style="page-break-inside: avoid; margin-top: 25px;">
        <div class="section-title">Rincian Transaksi & Biaya</div>
        <table class="record-box billing-table">
            <thead>
                <tr style="background-color: #f8fafc; font-weight: bold; font-size: 9px;">
                    <td style="border-bottom: 1px solid #e2e8f0; width: 70%;">Deskripsi Komponen Layanan</td>
                    <td style="border-bottom: 1px solid #e2e8f0; text-align: right; width: 30%;">Subtotal (IDR)</td>
                </tr>
            </thead>
            <tbody>
                <tr>
                    <td><strong>Jasa Konsultasi Dokter & Pemeriksaan Fisik</strong><br>
                        <span style="font-size: 9px; color: #64748b;">Pemeriksaan klinis komprehensif poli</span>
                    </td>
                    <td style="text-align: right; font-weight: bold; padding-top: 14px;">
                        Rp {{ number_format($biayaDokter, 0, ',', '.') }}
                    </td>
                </tr>
                <tr>
                    <td><strong>Pengadaan Farmasi / Obat-obatan</strong><br>
                        <span style="font-size: 9px; color: #64748b;">Apotek / rincian item obat yang diserahkan:</span>
                        <div class="item-obat-detail">
                            @forelse($rincianObat as $obat)
                                <table class="obat-row" cellpadding="0" cellspacing="0">
                                    <tr>
                                        <td style="padding: 2px 0; border: none;">
                                            <span>• {{ $obat['nama'] }}</span><br>
                                            <span class="obat-subtext">&nbsp;&nbsp;({{ $obat['qty'] }} pesanan x Rp {{ number_format($obat['harga'], 0, ',', '.') }})</span>
                                        </td>
                                        <td style="text-align: right; padding: 2px 0; border: none; font-weight: bold; color: #1e293b;">
                                            Rp {{ number_format($obat['total'], 0, ',', '.') }}
                                        </td>
                                    </tr>
                                </table>
                            @empty
                                <div style="color: #64748b; font-style: italic; font-size: 9px; padding: 4px 0;">• Tidak ada rincian pecahan item obat</div>
                            @endforelse
                        </div>
                    </td>
                    <td style="text-align: right; font-weight: bold; padding-top: 14px;">
                        Rp {{ number_format($biayaObat, 0, ',', '.') }}
                    </td>
                </tr>
                <tr>
                    <td><strong>Biaya Administrasi & Registrasi Digital</strong><br>
                        <span style="font-size: 9px; color: #64748b;">Pemeliharaan berkas rekam medis elektronik cloud</span>
                    </td>
                    <td style="text-align: right; font-weight: bold; padding-top: 14px;">
                        Rp {{ number_format($biayaAdmin, 0, ',', '.') }}
                    </td>
                </tr>
            </tbody>
        </table>

        <table width="100%" style="margin-top: 10px;" cellpadding="0" cellspacing="0">
            <tr>
                <td width="55%" style="font-size: 9px; color: #64748b; vertical-align: top; padding-left: 5px; line-height: 1.6;">
                    <strong>METODE PEMBAYARAN :</strong> {{ strtoupper($pembayaran->metode ?? 'CASH / VA') }}<br>
                    <strong>NOMOR REFERENSI   :</strong> {{ $pembayaran->payment_ref ?? '-' }}<br>
                    <strong>STATUS TAGIHAN   :</strong> 
                    <span style="font-weight: bold; color: #059669; background-color: #d1fae5; padding: 2px 6px; border-radius: 4px; font-size: 8px;">
                        {{ strtoupper($pembayaran->status ?? 'LUNAS') }}
                    </span>
                </td>
                <td width="45%" style="vertical-align: top;">
                    <div class="total-box">
                        <div style="font-size: 8px; text-transform: uppercase; opacity: 0.7; margin-bottom: 4px; letter-spacing: 0.5px;">Total Bersih Pembayaran</div>
                        <div style="font-size: 15px; font-weight: bold;">Rp {{ number_format($totalBersih, 0, ',', '.') }}</div>
                    </div>
                </td>
            </tr>
        </table>
    </div>
    @endif

    <table width="100%" style="margin-top: 50px; page-break-inside: avoid;" cellpadding="0" cellspacing="0">
        <tr>
            <td width="65%"></td>
            <td width="35%" style="text-align: center;">
                <p style="margin-bottom: 50px; font-size: 10px;">Dokter Pemeriksa,</p>
                <p style="font-weight: bold; margin-bottom: 0; color: #0f172a;">{{ $pendaftaran->dokter->name ?? '(..........................)' }}</p>
                <div style="border-bottom: 1.5px solid #334155; width: 150px; margin: 5px auto;"></div>
                <p style="font-size: 7px; color: #64748b; margin-top: 4px; font-weight: bold; line-height: 1.3;">
                    DIGITAL SIGNATURE VERIFIED<br>POLKES JOMBANG
                </p>
            </td>
        </tr>
    </table>

    <div class="footer">
        Dicetak otomatis pada {{ now()->translatedFormat('d/m/Y H:i:s') }} WIB | Keamanan ID Log: {{ bin2hex(random_bytes(4)) }}
    </div>

</body>
</html>