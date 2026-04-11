@extends('layout.app')

@section('content')

<link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">

<style>
    :root {
        --primary: #2563eb;
        --primary-soft: #eff6ff;
        --deep-slate: #0f172a;
        --slate-600: #475569;
        --slate-400: #94a3b8;
        --glass: rgba(255, 255, 255, 0.7);
        --border-glass: rgba(255, 255, 255, 0.5);
    }

    body, html {
        margin: 0;
        padding: 0;
        width: 100%;
        min-height: 100vh;
        background: #f8fafc;
        font-family: 'Plus Jakarta Sans', sans-serif;
        color: var(--deep-slate);
    }

    .ultra-canvas {
        background: 
            radial-gradient(circle at 0% 0%, rgba(37, 99, 235, 0.08) 0%, transparent 40%),
            radial-gradient(circle at 100% 100%, rgba(37, 99, 235, 0.05) 0%, transparent 40%),
            #f8fafc;
        min-height: 100vh;
        width: 100%;
        display: flex;
        flex-direction: column;
        padding-bottom: 100px;
    }

    .jkn-luxury-wrapper {
        max-width: 1200px;
        width: 92%;
        margin: 0 auto;
        padding: 80px 60px;
        background: var(--glass);
        backdrop-filter: blur(20px);
        -webkit-backdrop-filter: blur(20px);
        border-radius: 0 0 80px 80px;
        border: 1px solid var(--border-glass);
        border-top: none;
        box-shadow: 0 25px 50px -12px rgba(0, 0, 0, 0.03);
    }

    /* HEADER SECTION */
    .jkn-header {
        text-align: center;
        margin-bottom: 60px;
        animation: fadeInDown 0.8s ease-out;
    }

    .jkn-header h2 {
        font-size: 48px;
        font-weight: 800;
        letter-spacing: -0.04em;
        background: linear-gradient(135deg, var(--deep-slate) 30%, var(--primary));
        -webkit-background-clip: text;
        -webkit-text-fill-color: transparent;
        margin-bottom: 20px;
    }

    .jkn-header p {
        font-size: 18px;
        color: var(--slate-600);
        max-width: 650px;
        margin: 0 auto;
        line-height: 1.6;
        font-weight: 400;
    }

    /* DOWNLOAD AREA */
    .download-area {
        text-align: center;
        margin-bottom: 100px;
    }

    .btn-luxury {
        display: inline-flex;
        align-items: center;
        gap: 12px;
        background: var(--deep-slate);
        color: #fff;
        padding: 16px 36px;
        border-radius: 16px;
        font-size: 15px;
        font-weight: 600;
        text-decoration: none;
        transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
        box-shadow: 0 10px 25px -5px rgba(15, 23, 42, 0.2);
    }

    .btn-luxury:hover {
        transform: translateY(-2px);
        background: #000;
        box-shadow: 0 20px 30px -10px rgba(0, 0, 0, 0.3);
    }

    /* GRID TUTORIAL */
    .step-grid {
        display: grid;
        grid-template-columns: 1fr 1.2fr;
        gap: 80px;
        align-items: center;
        margin-bottom: 120px;
        opacity: 0;
        animation: fadeInUp 0.8s ease-out forwards;
    }

    .step-grid.reverse { 
        grid-template-columns: 1.2fr 1fr; 
    }

    .step-text h4 {
        font-size: 32px;
        font-weight: 700;
        color: var(--deep-slate);
        margin-bottom: 24px;
        letter-spacing: -0.02em;
    }

    .step-text ul {
        list-style: none;
        padding: 0;
        margin-bottom: 30px;
    }

    .step-text ul li {
        display: flex;
        align-items: center;
        padding: 12px 0;
        font-size: 16px;
        color: var(--slate-600);
        border-bottom: 1px solid rgba(0,0,0,0.03);
    }

    .step-text ul li strong {
        color: var(--primary);
        margin-left: 5px;
    }

    .step-text ul li::before {
        content: "✓";
        display: flex;
        align-items: center;
        justify-content: center;
        min-width: 24px;
        height: 24px;
        background: var(--primary-soft);
        color: var(--primary);
        border-radius: 50%;
        margin-right: 15px;
        font-size: 12px;
        font-weight: 800;
    }

    /* VIDEO PLAYER STYLING */
    .video-frame {
        position: relative;
        border-radius: 30px;
        overflow: hidden;
        padding-top: 56.25%;
        background: #000;
        box-shadow: 0 30px 60px -12px rgba(0, 0, 0, 0.25);
        border: 8px solid #fff;
    }

    .video-frame iframe {
        position: absolute;
        top: 0; left: 0; width: 100%; height: 100%; border: 0;
    }

    .yt-shortcut {
        display: inline-flex;
        align-items: center;
        color: var(--primary);
        text-decoration: none;
        font-size: 14px;
        font-weight: 700;
        transition: gap 0.3s;
        gap: 8px;
    }

    .yt-shortcut:hover {
        gap: 12px;
    }

    /* FOOTER BUTTON */
    .back-footer {
        text-align: center;
        margin-top: 40px;
    }

    .btn-dashboard {
        display: inline-flex;
        align-items: center;
        gap: 12px;
        background: #fff;
        color: var(--deep-slate) !important;
        text-decoration: none;
        font-weight: 700;
        font-size: 16px;
        padding: 18px 40px;
        border-radius: 20px;
        box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.1), 0 2px 4px -1px rgba(0, 0, 0, 0.06);
        transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
        border: 1px solid rgba(0,0,0,0.05);
    }

    .btn-dashboard:hover {
        transform: scale(1.02);
        box-shadow: 0 20px 25px -5px rgba(0, 0, 0, 0.1);
        background: var(--deep-slate);
        color: #fff !important;
    }

    /* ANIMATIONS */
    @keyframes fadeInDown {
        from { opacity: 0; transform: translateY(-20px); }
        to { opacity: 1; transform: translateY(0); }
    }

    @keyframes fadeInUp {
        from { opacity: 0; transform: translateY(40px); }
        to { opacity: 1; transform: translateY(0); }
    }

    @media (max-width: 1024px) {
        .step-grid, .step-grid.reverse { grid-template-columns: 1fr; gap: 40px; }
        .jkn-luxury-wrapper { padding: 40px 20px; border-radius: 0 0 40px 40px; }
        .jkn-header h2 { font-size: 32px; }
    }
</style>

<div class="ultra-canvas">
    <div class="jkn-luxury-wrapper">

        {{-- HEADER --}}
        <div class="jkn-header">
            <h2>Pendaftaran Pasien JKN (BPJS)</h2>
            <p>
                Pendaftaran layanan dilakukan melalui aplikasi resmi Mobile JKN.<br>
                Ikuti panduan lengkap untuk registrasi akun dan pendaftaran online.
            </p>
        </div>

        {{-- CTA AREA --}}
        <div class="download-area">
            <a href="https://play.google.com/store/apps/details?id=app.bpjs.mobile" target="_blank" class="btn-luxury">
                <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><path d="M21 15v4a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2v-4M7 10l5 5 5-5M12 15V3"/></svg>
                Dapatkan Aplikasi
            </a>
        </div>

        {{-- TUTORIAL 1 --}}
        <div class="step-grid">
            <div class="step-text">
                <h4>Pembuatan Akun Mobile JKN</h4>
                <ul>
                    <li>Unduh & buka Mobile JKN di ponsel Anda</li>
                    <li>Klik menu <strong> Daftar Baru</strong></li>
                    <li>Input Nomor Kartu BPJS / NIK KTP</li>
                    <li>Lakukan verifikasi melalui OTP</li>
                    <li>Tentukan password keamanan Anda</li>
                </ul>
                <a href="https://youtube.com/shorts/ddc21BfzVwQ" target="_blank" class="yt-shortcut">Video Panduan →</a>
            </div>
            <div class="video-frame">
                <iframe src="https://www.youtube.com/embed/ddc21BfzVwQ" allowfullscreen></iframe>
            </div>
        </div>

        {{-- TUTORIAL 2 --}}
        <div class="step-grid reverse" style="animation-delay: 0.2s;">
            <div class="video-frame">
                <iframe src="https://www.youtube.com/embed/5DCur3Yy4w8" allowfullscreen></iframe>
            </div>
            <div class="step-text">
                <h4>Pendaftaran Online di Mobile JKN</h4>
                <ul>
                    <li>Login menggunakan akun terdaftar</li>
                    <li>Pilih menu utama <strong> Pendaftaran Pelayanan</strong></li>
                    <li>Cari Fasilitas Kesehatan & Poli tujuan</li>
                    <li>Tentukan jadwal dokter dan tanggal</li>
                    <li>Konfirmasi dan dapatkan nomor antrian</li>
                </ul>
                <a href="https://youtube.com/shorts/5DCur3Yy4w8" target="_blank" class="yt-shortcut">Video Panduan →</a>
            </div>
        </div>

        {{-- KEMBALI KE DASHBOARD --}}
        <div class="back-footer">
            <a href="{{ route('pendaftaran.online') }}" class="btn-dashboard">
                <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="3" stroke-linecap="round" stroke-linejoin="round">
                    <line x1="19" y1="12" x2="5" y2="12"></line>
                    <polyline points="12 19 5 12 12 5"></polyline>
                </svg>
                Kembali ke Dashboard
            </a>
        </div>

    </div>
</div>

@endsection