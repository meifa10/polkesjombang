@extends('layout.app')

@section('content')
<link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
<link href="https://fonts.googleapis.com/css2?family=Courier+Prime:wght@400;700&display=swap" rel="stylesheet">
<script src="https://unpkg.com/@phosphor-icons/web"></script>

<style>
    :root {
        --c-primary: #0f172a;
        --c-accent: #4f46e5;
        --c-emerald: #10b981;
        --c-white: #ffffff;
        --c-text-main: #1e293b;
        --c-text-muted: #64748b;
        --card-shadow: 0 20px 25px -5px rgba(0, 0, 0, 0.03), 0 10px 10px -5px rgba(0, 0, 0, 0.02);
    }
    body { font-family: 'Plus Jakarta Sans', sans-serif; background-color: #f1f5f9; min-height: 100vh; }
    .container-wide { max-width: 1000px; margin: 60px auto; padding: 0 30px; }
    .hero-section { display: flex; justify-content: space-between; align-items: flex-end; margin-bottom: 60px; }
    .hero-title h1 { font-size: 42px; font-weight: 800; letter-spacing: -0.05em; line-height: 1; margin: 0; background: linear-gradient(135deg, var(--c-primary) 30%, var(--c-accent)); -webkit-background-clip: text; -webkit-text-fill-color: transparent; }
    .profile-glass { background: rgba(255, 255, 255, 0.8); backdrop-filter: blur(20px); border: 1px solid rgba(255, 255, 255, 0.5); border-radius: 40px; padding: 40px; display: flex; justify-content: space-between; align-items: center; margin-bottom: 40px; box-shadow: var(--card-shadow); }
    .user-flex { display: flex; align-items: center; gap: 24px; }
    .avatar-icon { width: 74px; height: 74px; background: var(--c-primary); color: white; border-radius: 24px; display: grid; place-items: center; font-size: 32px; box-shadow: 0 10px 20px rgba(15, 23, 42, 0.2); }
    .status-pill { display: inline-flex; align-items: center; gap: 6px; background: #ecfdf5; color: #059669; padding: 4px 12px; border-radius: 100px; font-size: 11px; font-weight: 700; margin-top: 6px; }
    .filter-bar { display: flex; gap: 15px; margin-bottom: 50px; }
    .search-wrapper { position: relative; flex: 1; }
    .search-wrapper i { position: absolute; left: 22px; top: 50%; transform: translateY(-50%); color: var(--c-text-muted); font-size: 20px; }
    .input-premium { width: 100%; padding: 20px 20px 20px 60px; border-radius: 24px; border: 1px solid transparent; background: var(--c-white); font-size: 15px; font-weight: 600; box-shadow: var(--card-shadow); transition: 0.3s; outline: none; }
    .date-premium { padding: 0 25px; border-radius: 24px; border: 1px solid transparent; background: var(--c-white); font-weight: 700; box-shadow: var(--card-shadow); cursor: pointer; outline: none; }
    .visit-card { background: var(--c-white); border-radius: 48px; padding: 45px; margin-bottom: 45px; border: 1px solid rgba(241, 245, 249, 0.5); }
    .visit-tag { font-size: 10px; font-weight: 800; color: var(--c-accent); background: #f0f0ff; padding: 6px 14px; border-radius: 12px; letter-spacing: 1px; }
    .visit-date { font-size: 24px; font-weight: 800; margin-top: 8px; letter-spacing: -0.03em; }
    .bento-layout { display: grid; grid-template-columns: repeat(3, 1fr); gap: 16px; }
    .bento-node { padding: 24px; border-radius: 28px; background: #f8fafc; border: 1px solid #f1f5f9; }
    .bento-node label { font-size: 9px; font-weight: 800; color: var(--c-text-muted); text-transform: uppercase; letter-spacing: 1.5px; margin-bottom: 12px; display: block; }
    .bento-node .text { font-size: 15px; font-weight: 600; line-height: 1.6; }
    .node-diagnosis { grid-column: span 2; background: #eff6ff; }
    .node-resep { grid-column: span 3; background: linear-gradient(135deg, #f0fdf4 0%, #ffffff 100%); border: 1px dashed var(--c-emerald); }
    .rincian-biaya-container { grid-column: span 3; background: #fafafa; border: 1px solid #e2e8f0; border-radius: 32px; padding: 30px; margin-top: 10px; font-family: 'Courier Prime', monospace; color: #000; }
    .dashed-separator { border-top: 2px dashed #a1a1aa; margin: 12px 0; }
    .btn-download { background: var(--c-primary); color: white; padding: 16px 28px; border-radius: 22px; font-weight: 700; text-decoration: none; display: flex; align-items: center; gap: 12px; transition: 0.3s; }
</style>

<div class="container-wide">
    <div class="hero-section">
        <div class="hero-title"><h1>Rekam Medis.</h1><p>Riwayat diagnosis dan catatan klinis Anda.</p></div>
        <a href="{{ route('dashboard') }}" class="btn-download" style="background: white; color: var(--c-primary); border: 1px solid #e2e8f0; box-shadow: none;">
            <i class="ph-bold ph-house-line"></i> Dashboard
        </a>
    </div>

    <div class="profile-glass">
        <div class="user-flex">
            <div class="avatar-icon"><i class="ph-fill ph-user"></i></div>
            <div class="user-meta">
                <h2>{{ Auth::user()->name }}</h2>
                <div class="status-pill"><i class="ph-fill ph-seal-check"></i> Akun Terverifikasi</div>
            </div>
        </div>
    </div>

    <form action="{{ route('pasien.rekammedis') }}" method="GET" id="filterForm">
        <div class="filter-bar">
            <div class="search-wrapper">
                <i class="ph-bold ph-magnifying-glass"></i>
                <input type="text" name="q" id="searchInput" value="{{ request('q') }}" class="input-premium" placeholder="Cari keluhan atau diagnosa...">
            </div>
            <input type="date" name="from" value="{{ request('from') }}" class="date-premium" onchange="this.form.submit()">
        </div>
    </form>

    @forelse($rekamMedis as $rm)
    @php
        $biayaDokter = (int)($rm->biaya_dokter ?? 0);
        $biayaAdmin = (int)($rm->biaya_admin ?? 0);
        $biayaObat = (int)($rm->total_obat ?? 0);
        $totalBersih = $biayaDokter + $biayaAdmin + $biayaObat;
    @endphp

    <div class="visit-card">
        <div class="card-top">
            <div>
                <div class="visit-tag">HASIL PEMERIKSAAN</div>
                <div class="visit-date">{{ \Carbon\Carbon::parse($rm->created_at)->locale('id')->translatedFormat('d F Y') }}</div>
            </div>
            <a href="{{ route('pasien.rekammedis.pdf', $rm->id) }}" target="_blank" class="btn-download">
                <i class="ph-bold ph-file-pdf"></i> Unduh Laporan
            </a>
        </div>

        <div class="bento-layout">
            <div class="bento-node node-diagnosis"><label>Diagnosis Medis</label><div class="text">{{ $rm->diagnosis }}</div></div>
            <div class="bento-node"><label>Unit Layanan</label><div class="text">{{ $rm->poli ?? 'Poli Umum' }}</div></div>
            <div class="bento-node"><label>Keluhan Pasien</label><div class="text">{{ $rm->keluhan }}</div></div>
            <div class="bento-node"><label>Tindakan Dokter</label><div class="text">{{ $rm->tindakan }}</div></div>
            <div class="bento-node node-resep"><label>Resep Obat</label><div class="text">{{ $rm->resep }}</div></div>

            <div class="rincian-biaya-container text-xs">
                <p style="font-family: 'Plus Jakarta Sans', sans-serif; font-size: 10px; font-weight: 800; color: #64748b; letter-spacing: 1.5px; text-transform: uppercase; margin-bottom: 10px;">
                    <i class="ph-bold ph-receipt mr-1"></i> Rincian Pembayaran
                </p>
                <div style="display: flex; justify-content: space-between; margin-bottom: 8px;">
                    <div><span style="font-weight: 700;">JASA DOKTER</span></div>
                    <span>Rp {{ number_format($biayaDokter, 0, ',', '.') }}</span>
                </div>
                <div style="display: flex; justify-content: space-between; margin-bottom: 8px;">
                    <div><span style="font-weight: 700;">ADMINISTRASI</span></div>
                    <span>Rp {{ number_format($biayaAdmin, 0, ',', '.') }}</span>
                </div>
                <div style="display: flex; justify-content: space-between; margin-bottom: 8px;">
                    <div><span style="font-weight: 700;">OBAT-OBATAN</span></div>
                    <span>Rp {{ number_format($biayaObat, 0, ',', '.') }}</span>
                </div>
                <div class="dashed-separator"></div>
                <div style="display: flex; justify-content: space-between; font-weight: 800; font-size: 14px;">
                    <span>TOTAL BERSIH</span>
                    <span>Rp {{ number_format($totalBersih, 0, ',', '.') }}</span>
                </div>
            </div>
        </div>
    </div>
    @empty
        <div style="text-align: center; padding: 100px 20px;">
            <p>Tidak ada catatan rekam medis ditemukan.</p>
        </div>
    @endforelse
</div>
@endsection