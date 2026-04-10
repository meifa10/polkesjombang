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

        /* Header Style */
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

        /* Patient Info Box */
        .patient-info-table {
            width: 100%;
            background-color: #f8fafc;
            padding: 15px;
            border-radius: 8px;
            margin-bottom: 25px;
        }
        .info-label {
            color: #64748b;
            font-size: 9px;
            text-transform: uppercase;
            font-weight: bold;
        }
        .info-value {
            font-size: 12px;
            font-weight: bold;
            color: #1e293b;
        }

        /* Section Title */
        .section-title {
            font-size: 13px;
            font-weight: bold;
            color: #0f172a;
            margin-bottom: 15px;
            border-left: 4px solid #2563eb;
            padding-left: 10px;
        }

        /* Medical Record Entry */
        .record-box {
            width: 100%;
            border: 1px solid #e2e8f0;
            border-radius: 10px;
            margin-bottom: 20px;
            border-collapse: collapse;
            overflow: hidden;
        }
        .record-header {
            background-color: #f1f5f9;
            padding: 8px 12px;
            font-weight: bold;
            border-bottom: 1px solid #e2e8f0;
        }
        .record-body {
            padding: 12px;
        }
        .content-table {
            width: 100%;
        }
        .content-table td {
            padding: 5px 0;
            vertical-align: top;
        }
        .content-label {
            width: 120px;
            font-weight: bold;
            color: #475569;
        }
        .content-text {
            color: #1e293b;
        }

        /* Prescription Tag */
        .prescription-area {
            margin-top: 10px;
            padding: 10px;
            background-color: #eff6ff;
            border-left: 3px solid #3b82f6;
        }

        /* Billing Table */
        .total-box {
            background-color: #0f172a;
            color: white;
            padding: 15px 20px;
            text-align: right;
            border-radius: 5px;
        }

        /* Footer */
        .footer {
            position: fixed;
            bottom: 0;
            width: 100%;
            text-align: center;
            font-size: 8px;
            color: #94a3b8;
            border-top: 1px solid #f1f5f9;
            padding-top: 5px;
        }
    </style>
</head>
<body>

    <table class="header-table">
        <tr>
            <td>
                <div class="hospital-name">Medical Center Digital<br> Polkes 05.09.15 Jombang</div>
                <div class="hospital-info">
                    Jl. KH. Wahid Hasyim No.28 B Jombang, Jawa Timur<br>
                    Telp / WA: 0877-7723-5386 | Email: support@medicalcenter.id
                </div>
            </td>
            <td style="text-align: right; vertical-align: middle;">
                <div style="font-size: 14px; font-weight: bold; color: #2563eb;">REKAM MEDIS PASIEN</div>
                <div style="font-size: 9px; color: #64748b;">ID REG: #REG-{{ str_pad($pendaftaran->id, 5, '0', STR_PAD_LEFT) }}</div>
            </td>
        </tr>
    </table>

    <table class="patient-info-table">
        <tr>
            <td width="33%">
                <div class="info-label">Nama Lengkap</div>
                <div class="info-value">{{ $pendaftaran->nama_pasien }}</div>
            </td>
            <td width="33%">
                <div class="info-label">Unit Layanan / Poli</div>
                <div class="info-value">{{ $pendaftaran->poli }}</div>
            </td>
            <td width="33%">
                <div class="info-label">Tanggal Cetak</div>
                <div class="info-value">{{ now()->translatedFormat('d F Y') }}</div>
            </td>
        </tr>
    </table>

    <div class="section-title">RIWAYAT PEMERIKSAAN</div>

    @if($rekamMedis->count() === 0)
        <div style="text-align: center; padding: 40px; color: #94a3b8; border: 1px dashed #cbd5e1; border-radius: 8px;">
            Belum ada data rekam medis yang tercatat dalam sistem.
        </div>
    @else
        @foreach($rekamMedis as $rm)
            <div class="record-box">
                <div class="record-header">
                    PEMERIKSAAN TANGGAL: {{ \Carbon\Carbon::parse($rm->created_at)->translatedFormat('d F Y') }}
                </div>
                <div class="record-body">
                    <table class="content-table">
                        <tr>
                            <td class="content-label">Keluhan Utama</td>
                            <td class="content-text">: {{ $rm->keluhan }}</td>
                        </tr>
                        <tr>
                            <td class="content-label">Diagnosis Medis</td>
                            <td class="content-text">: <strong>{{ $rm->diagnosis }}</strong></td>
                        </tr>
                        <tr>
                            <td class="content-label">Tindakan & Saran</td>
                            <td class="content-text">: {{ $rm->tindakan }}</td>
                        </tr>
                    </table>

                    <div class="prescription-area">
                        <div class="info-label" style="color: #1d4ed8; margin-bottom: 4px;">Resep Obat (Rx):</div>
                        <div class="content-text" style="font-style: italic;">{{ $rm->resep }}</div>
                    </div>
                </div>
            </div>
        @endforeach
    @endif

    @if(isset($pembayaran))
        <div style="page-break-inside: avoid; margin-top: 20px;">
            <div class="section-title">RINCIAN TRANSAKSI</div>
            <table class="content-table" style="margin-left: 10px; margin-bottom: 10px;">
                <tr>
                    <td width="150" class="info-label">Nomor Referensi</td>
                    <td>: <span style="font-family: monospace;">{{ $pembayaran->payment_ref ?? '-' }}</span></td>
                </tr>
                <tr>
                    <td class="info-label">Status Pembayaran</td>
                    <td>: 
                        <span style="font-weight: bold; color: {{ $pembayaran->status == 'lunas' ? '#059669' : '#dc2626' }}">
                            {{ strtoupper(str_replace('_',' ', $pembayaran->status)) }}
                        </span>
                    </td>
                </tr>
                <tr>
                    <td class="info-label">Metode Pembayaran</td>
                    <td>: {{ strtoupper($pembayaran->metode ?? 'Belum Dipilih') }}</td>
                </tr>
            </table>

            <table width="100%">
                <tr>
                    <td width="55%"></td>
                    <td width="45%">
                        <div class="total-box">
                            <table width="100%" style="color: white;">
                                <tr>
                                    <td style="font-size: 9px; text-transform: uppercase; vertical-align: middle;">Total Dibayarkan</td>
                                    <td style="text-align: right; font-size: 16px; font-weight: bold;">
                                        {{-- FIX: Membersihkan input sebelum format untuk menjaga konsistensi nominal --}}
                                        Rp {{ number_format((int) str_replace(['.', ','], '', $pembayaran->total_biaya), 0, ',', '.') }}
                                    </td>
                                </tr>
                            </table>
                        </div>
                    </td>
                </tr>
            </table>
        </div>
    @endif

    <table width="100%" style="margin-top: 50px;">
        <tr>
            <td width="70%"></td>
            <td width="30%" style="text-align: center;">
                <p>Petugas Administrasi,</p>
                <div style="margin-top: 60px; border-bottom: 1px solid #334155;"></div>
                <p style="font-size: 9px; color: #64748b;">Digital Signature Verified</p>
            </td>
        </tr>
    </table>

    <div class="footer">
        Dokumen ini diterbitkan secara digital oleh Sistem Informasi Polkes Jombang. <br>
        Dicetak pada {{ now()->format('d/m/Y H:i:s') }} - Validitas dokumen dapat dicek melalui sistem internal.
    </div>

</body>
</html>