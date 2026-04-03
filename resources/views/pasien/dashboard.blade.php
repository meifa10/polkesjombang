@extends('layout.app')

@section('content')
<link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">

<style>
    :root {
        --polkes-primary: #059669;
        --polkes-primary-light: #ecfdf5;
        --polkes-dark: #064e3b;
        --polkes-accent: #10b981;
        --background: #fdfdfd;
        --surface: #ffffff;
        --text-main: #0f172a;
        --text-muted: #64748b;
        --shadow-sm: 0 2px 4px rgba(0,0,0,0.02);
        --shadow-md: 0 10px 15px -3px rgba(0,0,0,0.04);
        --glass: rgba(255, 255, 255, 0.8);
    }

    body {
        background: #f8fafc;
        font-family: 'Plus Jakarta Sans', sans-serif;
        color: var(--text-main);
        letter-spacing: -0.01em;
    }

    .dashboard-wrapper {
        max-width: 1200px;
        margin: 30px auto;
        padding: 0 25px;
    }

    /* --- GREETING BANNER --- */
    .welcome-banner {
        background: var(--polkes-dark);
        background: linear-gradient(135deg, #064e3b 0%, #059669 100%);
        border-radius: 32px;
        padding: 45px;
        color: white;
        margin-bottom: 40px;
        position: relative;
        overflow: hidden;
        box-shadow: 0 20px 40px rgba(5, 150, 105, 0.15);
    }

    .welcome-banner::after {
        content: '';
        position: absolute;
        top: -50px;
        right: -50px;
        width: 200px;
        height: 200px;
        background: rgba(255,255,255,0.05);
        border-radius: 50%;
    }

    .welcome-banner p {
        font-size: 16px;
        font-weight: 500;
        opacity: 0.9;
        margin-bottom: 8px;
    }

    .welcome-banner h1 {
        font-size: 36px;
        font-weight: 800;
        margin: 0;
        letter-spacing: -0.03em;
    }

    .user-meta {
        display: flex;
        gap: 12px;
        margin-top: 25px;
    }

    .meta-tag {
        background: rgba(255, 255, 255, 0.12);
        backdrop-filter: blur(8px);
        padding: 8px 18px;
        border-radius: 14px;
        font-size: 13px;
        font-weight: 600;
        display: flex;
        align-items: center;
        gap: 8px;
        border: 1px solid rgba(255,255,255,0.1);
    }

    /* --- MENU GRID --- */
    .menu-grid {
        display: grid;
        grid-template-columns: repeat(4, 1fr);
        gap: 20px;
        margin-bottom: 40px;
    }

    .menu-card {
        background: var(--surface);
        padding: 24px;
        border-radius: 28px;
        text-decoration: none;
        border: 1px solid #f1f5f9;
        transition: all 0.4s cubic-bezier(0.4, 0, 0.2, 1);
        display: flex;
        flex-direction: column;
        align-items: flex-start;
        box-shadow: var(--shadow-sm);
    }

    .menu-card:hover {
        transform: translateY(-8px);
        box-shadow: 0 20px 25px -5px rgba(0, 0, 0, 0.05);
        border-color: var(--polkes-primary);
    }

    .icon-box {
        width: 56px;
        height: 56px;
        border-radius: 18px;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 22px;
        margin-bottom: 20px;
    }

    /* Palette Menu */
    .bg-pendaftaran { background: #ecfdf5; color: #10b981; }
    .bg-rekam { background: #eff6ff; color: #3b82f6; }
    .bg-antrian { background: #f5f3ff; color: #8b5cf6; }
    .bg-bayar { background: #fff7ed; color: #f97316; }

    .menu-card h3 {
        font-size: 17px;
        font-weight: 700;
        color: var(--text-main);
        margin: 0 0 8px 0;
    }

    .menu-card p {
        font-size: 13px;
        color: var(--text-muted);
        line-height: 1.5;
        margin: 0;
    }

    /* --- SECTION ACTIVITY --- */
    .status-section {
        background: white;
        border-radius: 32px;
        padding: 35px;
        border: 1px solid #f1f5f9;
        box-shadow: var(--shadow-md);
    }

    .section-header {
        display: flex;
        justify-content: space-between;
        align-items: center;
        margin-bottom: 30px;
    }

    .section-header h2 {
        font-size: 22px;
        font-weight: 800;
        color: var(--text-main);
        display: flex;
        align-items: center;
        gap: 12px;
    }

    .section-header h2::before {
        content: '';
        width: 4px;
        height: 24px;
        background: var(--polkes-primary);
        border-radius: 10px;
    }

    .custom-filter {
        background: #f8fafc;
        border: 1px solid #e2e8f0;
        padding: 10px 20px;
        border-radius: 14px;
        font-weight: 600;
        font-size: 14px;
        color: var(--text-main);
        outline: none;
        cursor: pointer;
    }

    /* --- VISIT CARD --- */
    .visit-card {
        display: flex;
        align-items: center;
        padding: 20px;
        background: #ffffff;
        border: 1px solid #f1f5f9;
        border-radius: 20px;
        margin-bottom: 16px;
        transition: 0.3s;
    }

    .visit-card:hover {
        background: #f8fafc;
        border-color: #e2e8f0;
    }

    .date-box {
        background: #f1f5f9;
        padding: 12px;
        border-radius: 16px;
        text-align: center;
        min-width: 75px;
        margin-right: 20px;
    }

    .date-box .day {
        display: block;
        font-size: 20px;
        font-weight: 800;
        color: var(--polkes-primary);
        line-height: 1;
    }

    .date-box .month {
        font-size: 11px;
        font-weight: 700;
        text-transform: uppercase;
        color: var(--text-muted);
    }

    .visit-main {
        flex-grow: 1;
    }

    .visit-main h4 {
        margin: 0 0 5px 0;
        font-size: 16px;
        font-weight: 700;
    }

    .visit-tags {
        display: flex;
        gap: 15px;
        align-items: center;
    }

    .status-pill {
        padding: 4px 12px;
        border-radius: 8px;
        font-size: 12px;
        font-weight: 700;
        text-transform: uppercase;
    }

    .pill-success { background: #d1fae5; color: #065f46; }
    .pill-process { background: #e0f2fe; color: #0369a1; }

    .antrian-info {
        font-size: 13px;
        color: var(--text-muted);
        display: flex;
        align-items: center;
        gap: 5px;
    }

    /* --- EMPTY STATE --- */
    .empty-state {
        text-align: center;
        padding: 60px 0;
    }

    .empty-illustration {
        width: 120px;
        margin-bottom: 25px;
        opacity: 0.8;
    }

    .btn-primary {
        background: var(--polkes-primary);
        color: white;
        padding: 12px 28px;
        border-radius: 14px;
        text-decoration: none;
        font-weight: 700;
        display: inline-block;
        transition: 0.3s;
        box-shadow: 0 10px 15px -3px rgba(5, 150, 105, 0.2);
    }

    .btn-primary:hover {
        background: var(--polkes-dark);
        transform: scale(1.02);
    }

    @media (max-width: 992px) {
        .menu-grid { grid-template-columns: repeat(2, 1fr); }
    }

    @media (max-width: 600px) {
        .menu-grid { grid-template-columns: 1fr; }
        .welcome-banner { padding: 30px; }
        .welcome-banner h1 { font-size: 26px; }
        .visit-card { flex-direction: column; align-items: flex-start; }
        .date-box { margin-bottom: 15px; width: 100%; }
    }
</style>

<div class="dashboard-wrapper">

    <header class="welcome-banner">
        <p>
            Halo, 
            @php
                $hour = date('H');
                if ($hour >= 5 && $hour < 11) {
                    echo 'Selamat Pagi 🌅';
                } elseif ($hour >= 11 && $hour < 15) {
                    echo 'Selamat Siang ☀️';
                } elseif ($hour >= 15 && $hour < 18) {
                    echo 'Selamat Sore 🌇';
                } else {
                    echo 'Selamat Malam 🌙';
                }
            @endphp
        </p>
        <h1>{{ Auth::user()->name }}</h1>
        
        <div class="user-meta">
            <div class="meta-tag">
                <i class="fa-solid fa-fingerprint"></i>
                {{ Auth::user()->no_identitas }}
            </div>
            <div class="meta-tag">
                <i class="fa-solid fa-shield-check text-emerald-300"></i>
                Verified Patient
            </div>
        </div>
    </header>

    <section class="menu-grid">
        <a href="{{ route('pendaftaran.umum') }}" class="menu-card">
            <div class="icon-box bg-pendaftaran">
                <i class="fa-solid fa-calendar-plus"></i>
            </div>
            <h3>Daftar Poli</h3>
            <p>Registrasi kunjungan ke klinik spesialis & umum.</p>
        </a>

        <a href="{{ route('pasien.rekammedis') }}" class="menu-card">
            <div class="icon-box bg-rekam">
                <i class="fa-solid fa-file-waveform"></i>
            </div>
            <h3>Rekam Medis</h3>
            <p>Lihat diagnosa, resep obat, dan riwayat klinis.</p>
        </a>

        <a href="{{ route('pasien.antrian') }}" class="menu-card">
            <div class="icon-box bg-antrian">
                <i class="fa-solid fa-clock-rotate-left"></i>
            </div>
            <h3>Cek Antrian</h3>
            <p>Pantau estimasi waktu panggil secara live.</p>
        </a>

        @if(isset($pembayaran) && $pembayaran)
        <a href="{{ route('payment.pay',$pembayaran->id) }}" class="menu-card">
            <div class="icon-box bg-bayar">
                <i class="fa-solid fa-credit-card"></i>
            </div>
            <h3>Pembayaran</h3>
            <p>Selesaikan tagihan administrasi Anda.</p>
        </a>
        @else
        <div class="menu-card" style="opacity: 0.7; cursor: not-allowed;">
            <div class="icon-box" style="background: #f1f5f9; color: #94a3b8;">
                <i class="fa-solid fa-wallet"></i>
            </div>
            <h3>Pembayaran</h3>
            <p>Tidak ada tagihan yang perlu dibayar.</p>
        </div>
        @endif
    </section>

    <main class="status-section">
        <div class="section-header">
            <h2>Aktivitas Terakhir</h2>
            <form method="GET">
                <select name="bulan" onchange="this.form.submit()" class="custom-filter">
                    <option value="">Filter Bulan</option>
                    @foreach(['Januari','Februari','Maret','April','Mei','Juni','Juli','Agustus','September','Oktober','November','Desember'] as $key=>$month)
                    <option value="{{ $key+1 }}" {{ request('bulan') == ($key+1) ? 'selected' : '' }}>
                        {{ $month }}
                    </option>
                    @endforeach
                </select>
            </form>
        </div>

        @if(isset($kunjungan) && $kunjungan->count() > 0)
            @foreach($kunjungan as $item)
            <div class="visit-card">
                <div class="date-box">
                    <span class="day">{{ $item->created_at ? $item->created_at->format('d') : '-' }}</span>
                    <span class="month">{{ $item->created_at ? $item->created_at->format('M Y') : '-' }}</span>
                </div>
                
                <div class="visit-main">
                    <h4>{{ $item->poli ?? 'Poli Umum' }}</h4>
                    <div class="visit-tags">
                        <span class="status-pill {{ $item->status == 'selesai' ? 'pill-success' : 'pill-process' }}">
                            {{ $item->status }}
                        </span>
                        <span class="antrian-info">
                            <i class="fa-solid fa-hashtag"></i>
                            Antrian: <strong>{{ $item->nomor_antrian }}</strong>
                        </span>
                    </div>
                </div>

                <div class="visit-action">
                    <i class="fa-solid fa-chevron-right text-slate-300"></i>
                </div>
            </div>
            @endforeach
        @else
            <div class="empty-state">
                <img src="https://cdn-icons-png.flaticon.com/512/3793/3793617.png" alt="Hospital" class="empty-illustration">
                <h3>Belum Ada Riwayat Kunjungan</h3>
                <p>Mulai konsultasi dengan dokter kami dengan mendaftar di layanan poli.</p>
                <a href="{{ route('pendaftaran.umum') }}" class="btn-primary">
                    Daftar Sekarang
                </a>
            </div>
        @endif
    </main>
</div>
@endsection