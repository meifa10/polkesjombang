@extends('layout.app')

@section('content')
<link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
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

    body {
        font-family: 'Plus Jakarta Sans', sans-serif;
        background-color: #f1f5f9;
        background-image: 
            radial-gradient(at 0% 0%, rgba(79, 70, 229, 0.06) 0px, transparent 40%), 
            radial-gradient(at 100% 100%, rgba(16, 185, 129, 0.06) 0px, transparent 40%);
        color: var(--c-text-main);
        min-height: 100vh;
    }

    .container-wide {
        max-width: 1000px;
        margin: 60px auto;
        padding: 0 30px;
    }

    /* --- HERO HEADER --- */
    .hero-section {
        display: flex;
        justify-content: space-between;
        align-items: flex-end;
        margin-bottom: 60px;
    }

    .hero-title h1 {
        font-size: 42px;
        font-weight: 800;
        letter-spacing: -0.05em;
        line-height: 1;
        margin: 0;
        background: linear-gradient(135deg, var(--c-primary) 30%, var(--c-accent));
        -webkit-background-clip: text;
        -webkit-text-fill-color: transparent;
    }

    .hero-title p {
        color: var(--c-text-muted);
        font-weight: 500;
        font-size: 15px;
        margin-top: 12px;
    }

    /* --- PROFILE CARD --- */
    .profile-glass {
        background: rgba(255, 255, 255, 0.8);
        backdrop-filter: blur(20px);
        border: 1px solid rgba(255, 255, 255, 0.5);
        border-radius: 40px;
        padding: 40px;
        display: flex;
        justify-content: space-between;
        align-items: center;
        margin-bottom: 40px;
        box-shadow: var(--card-shadow);
    }

    .user-flex {
        display: flex;
        align-items: center;
        gap: 24px;
    }

    .avatar-icon {
        width: 74px;
        height: 74px;
        background: var(--c-primary);
        color: white;
        border-radius: 24px;
        display: grid;
        place-items: center;
        font-size: 32px;
        box-shadow: 0 10px 20px rgba(15, 23, 42, 0.2);
    }

    .user-meta h2 {
        font-size: 26px;
        font-weight: 800;
        margin: 0;
        letter-spacing: -0.04em;
    }

    .status-pill {
        display: inline-flex;
        align-items: center;
        gap: 6px;
        background: #ecfdf5;
        color: #059669;
        padding: 4px 12px;
        border-radius: 100px;
        font-size: 11px;
        font-weight: 700;
        margin-top: 6px;
    }

    /* --- FILTER BAR --- */
    .filter-bar {
        display: flex;
        gap: 15px;
        margin-bottom: 50px;
    }

    .search-wrapper {
        position: relative;
        flex: 1;
    }

    .search-wrapper i {
        position: absolute;
        left: 22px;
        top: 50%;
        transform: translateY(-50%);
        color: var(--c-text-muted);
        font-size: 20px;
    }

    .input-premium {
        width: 100%;
        padding: 20px 20px 20px 60px;
        border-radius: 24px;
        border: 1px solid transparent;
        background: var(--c-white);
        font-size: 15px;
        font-weight: 600;
        box-shadow: var(--card-shadow);
        transition: 0.3s;
        outline: none;
    }

    .input-premium:focus {
        border-color: var(--c-accent);
        box-shadow: 0 0 0 5px rgba(79, 70, 229, 0.08);
    }

    .date-premium {
        padding: 0 25px;
        border-radius: 24px;
        border: 1px solid transparent;
        background: var(--c-white);
        font-weight: 700;
        box-shadow: var(--card-shadow);
        cursor: pointer;
        outline: none;
        color: var(--c-text-main);
    }

    /* --- RESET BUTTON --- */
    .btn-reset {
        display: flex;
        align-items: center;
        justify-content: center;
        padding: 0 25px;
        background: #fee2e2;
        color: #dc2626;
        border-radius: 24px;
        font-weight: 800;
        font-size: 13px;
        text-decoration: none;
        transition: 0.3s;
        border: 1px solid #fecaca;
    }

    .btn-reset:hover {
        background: #dc2626;
        color: white;
        transform: translateY(-2px);
    }

    /* --- VISIT CARD BENTO --- */
    .visit-card {
        background: var(--c-white);
        border-radius: 48px;
        padding: 45px;
        margin-bottom: 45px;
        border: 1px solid rgba(241, 245, 249, 0.5);
        transition: 0.5s cubic-bezier(0.175, 0.885, 0.32, 1.275);
    }

    .visit-card:hover {
        transform: translateY(-12px) scale(1.01);
        box-shadow: 0 40px 60px -15px rgba(0, 0, 0, 0.08);
    }

    .card-top {
        display: flex;
        justify-content: space-between;
        align-items: center;
        margin-bottom: 40px;
    }

    .visit-tag {
        font-size: 10px;
        font-weight: 800;
        color: var(--c-accent);
        background: #f0f0ff;
        padding: 6px 14px;
        border-radius: 12px;
        letter-spacing: 1px;
    }

    .visit-date {
        font-size: 24px;
        font-weight: 800;
        margin-top: 8px;
        letter-spacing: -0.03em;
    }

    .bento-layout {
        display: grid;
        grid-template-columns: repeat(3, 1fr);
        gap: 16px;
    }

    .bento-node {
        padding: 24px;
        border-radius: 28px;
        background: #f8fafc;
        border: 1px solid #f1f5f9;
    }

    .bento-node label {
        font-size: 9px;
        font-weight: 800;
        color: var(--c-text-muted);
        text-transform: uppercase;
        letter-spacing: 1.5px;
        margin-bottom: 12px;
        display: block;
    }

    .bento-node .text {
        font-size: 15px;
        font-weight: 600;
        line-height: 1.6;
    }

    .node-diagnosis { grid-column: span 2; background: #eff6ff; }
    .node-diagnosis .text { color: var(--c-accent); font-size: 18px; font-weight: 800; }
    .node-resep { grid-column: span 3; background: linear-gradient(135deg, #f0fdf4 0%, #ffffff 100%); border: 1px dashed var(--c-emerald); }
    .node-resep .text { color: #065f46; font-style: italic; }

    .btn-download {
        background: var(--c-primary);
        color: white;
        padding: 16px 28px;
        border-radius: 22px;
        font-weight: 700;
        text-decoration: none;
        display: flex;
        align-items: center;
        gap: 12px;
        transition: 0.3s;
    }

    .btn-download:hover { background: var(--c-accent); }

    @media (max-width: 850px) {
        .bento-layout { grid-template-columns: 1fr; }
        .node-diagnosis, .node-resep { grid-column: span 1; }
        .filter-bar { flex-direction: column; }
        .date-premium, .btn-reset { height: 60px; }
    }
</style>

<div class="container-wide">

    {{-- TOPBAR --}}
    <div class="hero-section">
        <div class="hero-title">
            <h1>Rekam Medis.</h1>
            <p>Riwayat diagnosis dan catatan klinis Anda.</p>
        </div>
        <a href="{{ route('dashboard') }}" class="btn-download" style="background: white; color: var(--c-primary); border: 1px solid #e2e8f0; box-shadow: none;">
            <i class="ph-bold ph-house-line"></i> Dashboard
        </a>
    </div>

    {{-- PROFILE --}}
    <div class="profile-glass">
        <div class="user-flex">
            <div class="avatar-icon">
                <i class="ph-fill ph-user"></i>
            </div>
            <div class="user-meta">
                <h2>{{ Auth::user()->name }}</h2>
                <div class="status-pill">
                    <i class="ph-fill ph-seal-check"></i> Akun Terverifikasi
                </div>
            </div>
        </div>
        <div style="text-align: right;">
            <p style="font-size: 10px; font-weight: 800; color: var(--c-text-muted); letter-spacing: 2px;">INSTANSI</p>
            <p style="font-size: 16px; font-weight: 800; color: var(--c-primary);">POLKES JOMBANG</p>
        </div>
    </div>

    {{-- FILTER SEARCH --}}
    <form action="{{ route('pasien.rekammedis') }}" method="GET" id="filterForm">
        <div class="filter-bar">
            <div class="search-wrapper">
                <i class="ph-bold ph-magnifying-glass"></i>
                <input type="text" name="q" id="searchInput" value="{{ request('q') }}" class="input-premium" placeholder="Cari keluhan atau diagnosa...">
            </div>
            <input type="date" name="from" value="{{ request('from') }}" class="date-premium" onchange="this.form.submit()">
            
            {{-- Tombol Reset Otomatis Muncul --}}
            @if(request('q') || request('from'))
                <a href="{{ route('pasien.rekammedis') }}" class="btn-reset">
                    <i class="ph-bold ph-arrows-counter-clockwise mr-2"></i> Reset
                </a>
            @endif
        </div>
    </form>

    {{-- LIST --}}
    @forelse($rekamMedis as $rm)
        <div class="visit-card">
            <div class="card-top">
                <div>
                    <div class="visit-tag">HASIL PEMERIKSAAN</div>
                    <div class="visit-date">{{ \Carbon\Carbon::parse($rm->created_at)->translatedFormat('d F Y') }}</div>
                </div>
                <a href="{{ route('pasien.rekammedis.pdf', $rm->id) }}" target="_blank" class="btn-download">
                    <i class="ph-bold ph-file-pdf"></i> Unduh Laporan
                </a>
            </div>

            <div class="bento-layout">
                <div class="bento-node node-diagnosis">
                    <label>Diagnosis Medis</label>
                    <div class="text">{{ $rm->diagnosis }}</div>
                </div>
                <div class="bento-node">
                    <label>Unit Layanan</label>
                    <div class="text">{{ $rm->poli ?? 'Poli Umum' }}</div>
                </div>
                <div class="bento-node">
                    <label>Keluhan Pasien</label>
                    <div class="text">{{ $rm->keluhan }}</div>
                </div>
                <div class="bento-node">
                    <label>Tindakan Dokter</label>
                    <div class="text">{{ $rm->tindakan }}</div>
                </div>
                <div class="bento-node node-resep">
                    <label>Resep Obat & Aturan Pakai</label>
                    <div class="text">{{ $rm->resep }}</div>
                </div>
            </div>
        </div>
    @empty
        <div style="text-align: center; padding: 120px 20px; background: white; border-radius: 50px; border: 1px dashed #cbd5e1;">
            <i class="ph-light ph-folder-open" style="font-size: 80px; color: #cbd5e1; margin-bottom: 24px;"></i>
            <h2 style="font-weight: 800; color: var(--c-primary);">Tidak ada catatan ditemukan.</h2>
            <p style="color: var(--c-text-muted);">Silakan atur ulang pencarian untuk melihat semua data.</p>
        </div>
    @endforelse

</div>

<script>
    let timer;
    document.getElementById('searchInput').addEventListener('input', function() {
        clearTimeout(timer);
        timer = setTimeout(() => {
            document.getElementById('filterForm').submit();
        }, 1000); 
    });
</script>
@endsection