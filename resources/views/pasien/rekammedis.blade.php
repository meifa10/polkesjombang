@extends('layout.app')

@section('content')
<link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800&family=JetBrains+Mono:wght@500&display=swap" rel="stylesheet">

<style>
    :root {
        --clinic-primary: #0f172a;    /* Navy Dark */
        --clinic-accent: #2563eb;     /* Blue Royal */
        --clinic-success: #059669;    /* Green Emerald */
        --clinic-bg: #f8fafc;         /* Slate Light */
        --clinic-card: #ffffff;
        --clinic-border: #e2e8f0;
        --clinic-text-main: #1e293b;
        --clinic-text-muted: #64748b;
    }

    body {
        font-family: 'Inter', sans-serif;
        background-color: var(--clinic-bg);
        color: var(--clinic-text-main);
        line-height: 1.6;
    }

    .medical-container {
        max-width: 1000px;
        margin: 40px auto;
        padding: 0 20px;
    }

    /* --- Judul Halaman --- */
    .top-header {
        display: flex;
        justify-content: space-between;
        align-items: center;
        margin-bottom: 35px;
    }

    .brand-section h1 {
        font-size: 30px;
        font-weight: 800;
        letter-spacing: -0.03em;
        margin: 0;
        color: var(--clinic-primary);
    }

    .brand-section p {
        color: var(--clinic-text-muted);
        font-size: 15px;
        margin: 4px 0 0 0;
    }

    /* --- Kartu Profil Pasien --- */
    .patient-brief {
        background: var(--clinic-primary);
        color: white;
        border-radius: 24px;
        padding: 28px 35px;
        display: flex;
        justify-content: space-between;
        align-items: center;
        margin-bottom: 40px;
        box-shadow: 0 20px 25px -5px rgba(15, 23, 42, 0.15);
    }

    .patient-id-badge {
        font-family: 'JetBrains Mono', monospace;
        background: rgba(255,255,255,0.1);
        padding: 8px 15px;
        border-radius: 10px;
        font-size: 14px;
        border: 1px solid rgba(255,255,255,0.2);
        letter-spacing: 1px;
    }

    /* --- Desain Timeline --- */
    .timeline-wrapper {
        position: relative;
        padding-left: 25px;
    }

    .timeline-wrapper::before {
        content: '';
        position: absolute;
        left: 0;
        top: 0;
        height: 100%;
        width: 2px;
        background: var(--clinic-border);
    }

    .record-entry {
        position: relative;
        background: var(--clinic-card);
        border: 1px solid var(--clinic-border);
        border-radius: 24px;
        padding: 32px;
        margin-bottom: 35px;
        transition: transform 0.3s ease, box-shadow 0.3s ease;
    }

    .record-entry:hover {
        transform: translateY(-5px);
        box-shadow: 0 15px 30px rgba(0,0,0,0.06);
        border-color: var(--clinic-accent);
    }

    .record-entry::before {
        content: '';
        position: absolute;
        left: -31px;
        top: 38px;
        width: 12px;
        height: 12px;
        border-radius: 50%;
        background: var(--clinic-accent);
        border: 4px solid var(--clinic-bg);
    }

    /* --- Header Baris Medis --- */
    .entry-header {
        display: flex;
        justify-content: space-between;
        align-items: center;
        margin-bottom: 25px;
        padding-bottom: 20px;
        border-bottom: 1px solid #f1f5f9;
    }

    .date-box {
        display: flex;
        align-items: center;
        gap: 15px;
    }

    .calendar-tag {
        background: #eff6ff;
        color: var(--clinic-accent);
        width: 48px;
        height: 48px;
        border-radius: 14px;
        display: grid;
        place-items: center;
    }

    /* --- Grid Informasi Medis --- */
    .medical-grid {
        display: grid;
        grid-template-columns: repeat(2, 1fr);
        gap: 20px;
    }

    .grid-item {
        display: flex;
        flex-direction: column;
        gap: 8px;
    }

    .grid-item label {
        font-size: 11px;
        font-weight: 800;
        color: var(--clinic-text-muted);
        text-transform: uppercase;
        letter-spacing: 0.05em;
    }

    .grid-item .value {
        font-size: 15px;
        color: var(--clinic-text-main);
        background: #f8fafc;
        padding: 15px;
        border-radius: 14px;
        border: 1px solid #f1f5f9;
        min-height: 45px;
    }

    .prescription-highlight {
        grid-column: span 2;
        background: #f0fdf4 !important; /* Hijau Segar */
        border: 1px dashed #bbf7d0 !important;
        color: #166534 !important;
    }

    /* --- Tombol & Badge --- */
    .badge-verified {
        background: #d1fae5;
        color: #065f46;
        padding: 6px 14px;
        border-radius: 100px;
        font-size: 11px;
        font-weight: 700;
    }

    .btn-download-item {
        background: #ffffff;
        color: var(--clinic-accent);
        border: 1.5px solid var(--clinic-accent);
        padding: 10px 18px;
        border-radius: 12px;
        font-size: 13px;
        font-weight: 700;
        text-decoration: none;
        display: inline-flex;
        align-items: center;
        gap: 10px;
        transition: 0.2s;
    }

    .btn-download-item:hover {
        background: var(--clinic-accent);
        color: white;
    }

    /* --- Ringkasan Pembayaran --- */
    .billing-card {
        background: white;
        border: 2px solid var(--clinic-primary);
        border-radius: 24px;
        padding: 30px 40px;
        display: flex;
        justify-content: space-between;
        align-items: center;
        margin-top: 50px;
        box-shadow: 0 10px 20px rgba(0,0,0,0.05);
    }

    @media (max-width: 768px) {
        .medical-grid { grid-template-columns: 1fr; }
        .prescription-highlight { grid-column: span 1; }
        .entry-header { flex-direction: column; align-items: flex-start; gap: 15px; }
        .billing-card { flex-direction: column; text-align: center; gap: 20px; }
    }
</style>

<div class="medical-container">

    {{-- HEADER HALAMAN --}}
    <div class="top-header">
        <div class="brand-section">
            <h1>Riwayat Rekam Medis</h1>
            <p>Data klinis dan pemeriksaan kesehatan Anda</p>
        </div>
        <a href="{{ route('dashboard') }}" class="btn-download-item" style="border-color: #cbd5e1; color: #64748b;">
            <svg width="20" height="20" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path></svg>
            Dashboard
        </a>
    </div>

    {{-- KARTU PROFIL --}}
    <div class="patient-brief">
        <div style="display: flex; align-items: center; gap: 25px;">
            <div style="width: 65px; height: 65px; background: rgba(255,255,255,0.1); border-radius: 20px; display: grid; place-items: center; font-size: 28px; border: 1px solid rgba(255,255,255,0.2);">
                👤
            </div>
            <div>
                <div style="font-size: 14px; opacity: 0.7; font-weight: 500;">Pasien Terdaftar</div>
                <div style="font-size: 24px; font-weight: 800; letter-spacing: -0.02em;">{{ Auth::user()->name }}</div>
            </div>
        </div>
        <div style="text-align: right;">
            <div style="font-size: 14px; opacity: 0.7; margin-bottom: 5px;">Nomor Rekam Medis</div>
            <span class="patient-id-badge">{{ Auth::user()->no_identitas }}</span>
        </div>
    </div>

    {{-- DAFTAR KUNJUNGAN --}}
    <div class="timeline-wrapper">
        @forelse($rekamMedis as $rm)
            <div class="record-entry">
                <div class="entry-header">
                    <div class="date-box">
                        <div class="calendar-tag">
                            <svg width="24" height="24" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path></svg>
                        </div>
                        <div>
                            <div style="font-size: 18px; font-weight: 800; color: var(--clinic-primary);">{{ \Carbon\Carbon::parse($rm->created_at)->translatedFormat('d F Y') }}</div>
                            <div style="font-size: 12px; color: var(--clinic-text-muted); font-family: 'JetBrains Mono';">No. Referensi: #RM-{{ str_pad($rm->id, 5, '0', STR_PAD_LEFT) }}</div>
                        </div>
                    </div>
                    
                    {{-- Ganti bagian tombol cetak di file index Anda dengan ini --}}
                    <div style="display: flex; align-items: center; gap: 15px;">
                        <span class="badge-verified">✓ Terverifikasi</span>
                        
                        {{-- Pastikan name route ini sesuai dengan yang ada di web.php --}}
                        <a href="{{ route('pasien.rekammedis.pdf', $rm->id) }}" target="_blank" class="btn-download-item">
                            <svg width="18" height="18" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4"></path>
                            </svg>
                            Cetak PDF
                        </a>
                    </div>
                </div>

                <div class="medical-grid">
                    <div class="grid-item">
                        <label>Keluhan Utama</label>
                        <div class="value">{{ $rm->keluhan }}</div>
                    </div>
                    <div class="grid-item">
                        <label>Diagnosa Dokter</label>
                        <div class="value" style="font-weight: 700; color: var(--clinic-accent);">{{ $rm->diagnosis }}</div>
                    </div>
                    <div class="grid-item">
                        <label>Tindakan & Saran Medis</label>
                        <div class="value">{{ $rm->tindakan }}</div>
                    </div>
                    <div class="grid-item">
                        <label>Poli / Unit Layanan</label>
                        <div class="value">{{ $pendaftaran->poli ?? 'Layanan Umum' }}</div>
                    </div>
                    <div class="grid-item prescription-highlight">
                        <label style="color: #166534;">Resep Obat & Aturan Pakai</label>
                        <div style="font-weight: 600; font-size: 15px;">{{ $rm->resep }}</div>
                    </div>
                </div>
            </div>
        @empty
            <div style="background: white; border-radius: 24px; padding: 100px 20px; text-align: center; border: 1px dashed #cbd5e1;">
                <div style="font-size: 60px; margin-bottom: 20px;">📁</div>
                <h3 style="font-weight: 800; color: var(--clinic-primary); font-size: 22px;">Data Belum Tersedia</h3>
                <p style="color: var(--clinic-text-muted); max-width: 400px; margin: 10px auto;">Riwayat rekam medis Anda akan muncul secara otomatis setelah sesi pemeriksaan dengan dokter selesai.</p>
            </div>
        @endforelse
    </div>

    {{-- RINGKASAN BIAYA --}}
    @if(isset($pembayaran))
        <div class="billing-card">
            <div>
                <div style="font-size: 13px; text-transform: uppercase; font-weight: 700; color: var(--clinic-text-muted); margin-bottom: 5px;">Total Biaya Kunjungan Terakhir</div>
                <div style="font-size: 36px; font-weight: 900; color: var(--clinic-primary); letter-spacing: -1px;">
                    <span style="font-size: 18px; font-weight: 600; color: var(--clinic-accent);">Rp</span> {{ number_format($pembayaran->total_biaya, 0, ',', '.') }}
                </div>
            </div>
            <div style="text-align: right;">
                @if($pembayaran->status === 'lunas')
                    <div style="background: var(--clinic-primary); color: white; padding: 12px 25px; border-radius: 14px; font-weight: 800; font-size: 14px;">LUNAS TERBAYAR</div>
                @else
                    <div style="background: #fef2f2; color: #991b1b; padding: 12px 25px; border-radius: 14px; font-weight: 800; font-size: 14px; border: 1px solid #fee2e2;">MENUNGGU PEMBAYARAN</div>
                @endif
                <div style="margin-top: 12px; font-size: 12px; color: var(--clinic-text-muted); font-family: 'JetBrains Mono'; uppercase">No. Faktur: INV-{{ $pembayaran->id }}{{ date('ymd') }}</div>
            </div>
        </div>
    @endif

</div>
@endsection