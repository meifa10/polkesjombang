<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="utf-8">
    <title>Rekam Medis - {{ $pendaftaran->nama_pasien }}</title>
    <style>
        @page { 
            margin: 1.5cm; 
        }
        body { 
            font-family: 'Helvetica', 'Arial', sans-serif; 
            font-size: 11px; 
            color: #334155; 
            line-height: 1.5; 
            margin: 0;
            padding: 0;
        }

        /* --- Header Section --- */
        .header-table { 
            width: 100%; 
            border-bottom: 2px solid #0f172a; 
            padding-bottom: 10px; 
            margin-bottom: 20px; 
        }
        .hospital-name { 
            font-size: 18px; 
            font-weight: bold; 
            color: #0f172a; 
            text-transform: uppercase; 
            letter-spacing: 1px;
        }
        .hospital-info { 
            font-size: 9px; 
            color: #64748b; 
        }

        /* --- Patient Info Box --- */
        .patient-info-table { 
            width: 100%; 
            background-color: #f8fafc; 
            padding: 15px; 
            border-radius: 8px; 
            margin-bottom: 25px; 
            border: 1px solid #e2e8f0;
        }
        .info-label { 
            color: #64748b; 
            font-size: 8px; 
            text-transform: uppercase; 
            font-weight: bold; 
        }
        .info-value { 
            font-size: 11px; 
            font-weight: bold; 
            color: #1e293b; 
        }

        /* --- Content Sections --- */
        .section-title { 
            font-size: 12px; 
            font-weight: bold; 
            color: #0f172a; 
            margin-bottom: 12px; 
            border-left: 4px solid #059669; 
            padding-left: 10px; 
            text-transform: uppercase;
        }

        .record-box { 
            width: 100%; 
            border: 1px solid #e2e8f0; 
            border-radius: 8px; 
            margin-bottom: 15px; 
            border-collapse: collapse; 
            overflow: hidden;
        }
        .record-header { 
            background-color: #f1f5f9; 
            padding: 8px 12px; 
            font-weight: bold; 
            font-size: 10px;
            border-bottom: 1px solid #e2e8f0; 
        }
        .record-body { 
            padding: 12px; 
        }

        .prescription-area { 
            margin-top: 10px; 
            padding: 10px; 
            background-color: #f0fdf4; 
            border-left: 3px solid #059669; 
            border-radius: 4px;
        }

        /* --- Billing Section --- */
        .billing-table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 5px;
        }
        .billing-table td {
            padding: 8px;
            border-bottom: 1px solid #f1f5f9;
        }
        .total-box { 
            background-color: #0f172a; 
            color: white; 
            padding: 12px 15px; 
            text-align: right; 
            border-radius: 6px; 
        }

        .footer { 
            position: fixed; 
            bottom: 0; 
            width: 100%; 
            text-align: center; 
            font-size: 8px; 
            color: #94a3b8; 
            border-top: 1px solid #f1f5f9; 
            padding-top: 8px; 
        }
    </style>
</head>
<body>

    <!-- Header Bisnis -->
    <table class="header-table">
        <tr>
            <td>
                <div class="hospital-name">Medical Center Digital<br> Polkes 05.09.15 Jombang</div>
                <div class="hospital-info">
                    Jl. KH. Wahid Hasyim No.28 B Jombang, Jawa Timur<br>
                    Telp / WA: 0877-7723-5386 | Sistem Rekam Medis Terintegrasi
                </div>
            </td>
            <td style="text-align: right; vertical-align: middle;">
                <div style="font-size: 13px; font-weight: bold; color: #059669;">DOKUMEN REKAM MEDIS</div>
                <div style="font-size: 9px; color: #64748b; margin-top: 3px;">
                    REG-ID: #{{ str_pad($pendaftaran->id, 6, '0', STR_PAD_LEFT) }}
                </div>
            </td>
        </tr>
    </table>

    <!-- Ringkasan Profil Pasien -->
    <table class="patient-info-table">
        <tr>
            <td width="25%">
                <div class="info-label">Nama Pasien</div>
                <div class="info-value">{{ strtoupper($pendaftaran->nama_pasien) }}</div>
            </td>
            <td width="25%">
                <div class="info-label">Unit Layanan (Poli)</div>
                <div class="info-value">{{ $pendaftaran->poli }}</div>
            </td>
            <td width="25%">
                <div class="info-label">Dokter Pemeriksa</div>
                <div class="info-value">{{ $pendaftaran->dokter->name ?? 'Belum Ditentukan' }}</div>
            </td>
            <td width="25%">
                <div class="info-label">Waktu Cetak</div>
                <div class="info-value">{{ now()->translatedFormat('d M Y, H:i') }} WIB</div>
            </td>
        </tr>
    </table>

    <!-- Bagian Riwayat Klinis -->
    <div class="section-title">Hasil Pemeriksaan Klinis</div>
    @forelse($rekamMedis as $rm)
        <div class="record-box">
            <div class="record-header">
                <table width="100%">
                    <tr>
                        <td>KUNJUNGAN TANGGAL: {{ \Carbon\Carbon::parse($rm->created_at)->translatedFormat('d F Y') }}</td>
                        <td style="text-align: right; font-weight: normal; font-size: 9px; color: #64748b;">
                            DPJP: {{ $pendaftaran->dokter->name ?? '-' }}
                        </td>
                    </tr>
                </table>
            </div>
            <div class="record-body">
                <table width="100%" cellpadding="0" cellspacing="0">
                    <tr>
                        <td width="110" style="padding-bottom: 5px;"><strong>Keluhan</strong></td>
                        <td style="padding-bottom: 5px;">: {{ $rm->keluhan }}</td>
                    </tr>
                    <tr>
                        <td style="padding-bottom: 5px;"><strong>Diagnosis</strong></td>
                        <td style="padding-bottom: 5px;">: <strong>{{ $rm->diagnosis }}</strong></td>
                    </tr>
                    <tr>
                        <td style="padding-bottom: 5px;"><strong>Tindakan</strong></td>
                        <td style="padding-bottom: 5px;">: {{ $rm->tindakan }}</td>
                    </tr>
                </table>
                
                <div class="prescription-area">
                    <div class="info-label" style="color: #059669; margin-bottom: 3px;">Instruksi Resep / Terapi Obat:</div>
                    <div style="font-style: italic; color: #1e293b; font-size: 10px;">
                        {{ $rm->resep != '-' ? $rm->resep : 'Tidak ada resep obat untuk kunjungan ini.' }}
                    </div>
                </div>
            </div>
        </div>
    @empty
        <p style="text-align: center; color: #94a3b8; padding: 20px;">Data rekam medis tidak ditemukan.</p>
    @endforelse

    <!-- Bagian Rincian Biaya (Billing) -->
    @if(isset($pembayaran))
    <div style="page-break-inside: avoid; margin-top: 10px;">
        <div class="section-title">Rincian Transaksi & Biaya</div>
        
        <table class="record-box billing-table">
            <tr style="background-color: #f8fafc; font-weight: bold; font-size: 9px;">
                <td style="border-bottom: 1px solid #e2e8f0;">Deskripsi Layanan</td>
                <td style="border-bottom: 1px solid #e2e8f0; text-align: right;">Subtotal (IDR)</td>
            </tr>
            <tr>
                <td>Jasa Konsultasi Dokter & Pemeriksaan Fisik ({{ $pendaftaran->dokter->name ?? 'Umum' }})</td>
                <td style="text-align: right;">{{ number_format((int)$pembayaran->biaya_dokter, 0, ',', '.') }}</td>
            </tr>
            <tr>
                <td>
                    <strong>Pengadaan Obat-obatan</strong><br>
                    <span style="font-size: 7px; color: #94a3b8;">*Dihitung otomatis berdasarkan item resep</span>
                </td>
                <td style="text-align: right; vertical-align: top;">
                    {{ number_format((int)$pembayaran->total_obat, 0, ',', '.') }}
                </td>
            </tr>
            <tr>
                <td>Biaya Administrasi & Registrasi Digital</td>
                <td style="text-align: right;">{{ number_format((int)$pembayaran->biaya_admin, 0, ',', '.') }}</td>
            </tr>
        </table>

        <!-- Ringkasan Status & Total -->
        <table width="100%" style="margin-top: 10px;">
            <tr>
                <td width="55%" style="font-size: 8px; color: #64748b; vertical-align: top; padding-left: 5px;">
                    <strong>METODE PEMBAYARAN:</strong> {{ strtoupper($pembayaran->metode ?? 'N/A') }}<br>
                    <strong>NOMOR REFERENSI:</strong> {{ $pembayaran->payment_ref ?? '-' }}<br>
                    <strong>STATUS:</strong> 
                    <span style="font-weight: bold; color: {{ $pembayaran->status == 'lunas' ? '#059669' : '#dc2626' }}">
                        {{ strtoupper($pembayaran->status) }}
                    </span>
                </td>
                <td width="45%">
                    <div class="total-box">
                        <div style="font-size: 8px; text-transform: uppercase; opacity: 0.8; margin-bottom: 3px;">Total Tagihan Akhir</div>
                        <div style="font-size: 15px; font-weight: bold;">Rp {{ number_format((int)$pembayaran->total_biaya, 0, ',', '.') }}</div>
                    </div>
                </td>
            </tr>
        </table>
    </div>
    @endif

    <!-- Tanda Tangan -->
    <table width="100%" style="margin-top: 40px; page-break-inside: avoid;">
        <tr>
            <td width="65%"></td>
            <td width="35%" style="text-align: center;">
                <p style="margin-bottom: 45px;">Dokter Pemeriksa,</p>
                <p style="font-weight: bold; margin-bottom: 0;">{{ $pendaftaran->dokter->name ?? '(..........................)' }}</p>
                <div style="border-bottom: 1.5px solid #334155; width: 140px; margin: 5px auto;"></div>
                <p style="font-size: 7px; color: #64748b; margin-top: 4px; font-weight: bold;">
                    DIGITAL SIGNATURE VERIFIED<br>
                    POLKES JOMBANG
                </p>
            </td>
        </tr>
    </table>

    <div class="footer">
        Dokumen ini sah dan diterbitkan secara elektronik oleh Sistem Informasi Polkes Jombang.<br>
        Dicetak pada {{ now()->translatedFormat('d/m/Y H:i:s') }} | ID Log: {{ bin2hex(random_bytes(4)) }}
    </div>

</body>
</html>