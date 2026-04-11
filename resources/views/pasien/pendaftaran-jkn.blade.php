@extends('layout.app')

@section('content')

<link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600&family=Plus+Jakarta+Sans:wght@700;800&display=swap" rel="stylesheet">

<style>
    :root {
        --deep-slate: #0f172a;
        --ocean: #2563eb;
        --soft-text: #64748b;
        --luxury-shadow: 0 50px 100px -20px rgba(0, 0, 0, 0.06);
    }

    body, html {
        margin: 0;
        padding: 0;
        width: 100%;
        min-height: 100vh;
        background: #fdfdfd;
        font-family: 'Inter', sans-serif;
    }

    .ultra-canvas {
        background: radial-gradient(at 0% 0%, rgba(224, 242, 254, 0.5) 0, transparent 50%), 
                    radial-gradient(at 100% 100%, rgba(254, 243, 199, 0.3) 0, transparent 50%);
        min-height: 100vh;
        width: 100%;
        display: flex;
        flex-direction: column;
    }

    .jkn-luxury-wrapper {
        max-width: 1300px;
        width: 95%;
        margin: 0 auto;
        padding: 100px 80px;
        background: rgba(255, 255, 255, 0.4);
        backdrop-filter: blur(40px);
        -webkit-backdrop-filter: blur(40px);
        border-radius: 0 0 100px 100px;
        border: 1px solid rgba(255, 255, 255, 0.8);
        border-top: none;
        box-shadow: var(--luxury-shadow);
    }

    /* HEADER - Teks 2 Baris */
    .jkn-header {
        text-align: center;
        margin-bottom: 90px;
    }

    .jkn-header h2 {
        font-family: 'Plus Jakarta Sans', sans-serif;
        font-size: 42px;
        font-weight: 800;
        letter-spacing: -2px;
        color: var(--deep-slate);
        margin-bottom: 15px;
    }

    .jkn-header p {
        font-size: 16px;
        color: var(--soft-text);
        letter-spacing: 0.2px;
        max-width: 600px; /* Diatur agar teks pecah jadi 2 baris otomatis */
        margin: 0 auto;
        line-height: 1.8;
        font-weight: 500;
    }

    .download-area {
        text-align: center;
        margin-bottom: 120px;
    }

    .btn-luxury {
        display: inline-flex;
        align-items: center;
        gap: 12px;
        background: var(--deep-slate);
        color: #fff;
        padding: 18px 45px;
        border-radius: 100px;
        font-size: 15px;
        font-weight: 600;
        text-decoration: none;
        transition: 0.4s all;
        box-shadow: 0 20px 40px rgba(0,0,0,0.1);
    }

    .btn-luxury:hover {
        transform: translateY(-3px);
        background: #000;
    }

    .step-grid {
        display: grid;
        grid-template-columns: 1fr 1.5fr;
        gap: 100px;
        align-items: center;
        margin-bottom: 150px;
    }

    .step-grid.reverse { grid-template-columns: 1.5fr 1fr; }

    .step-text h4 {
        font-family: 'Plus Jakarta Sans', sans-serif;
        font-size: 28px;
        font-weight: 700;
        color: var(--deep-slate);
        margin-bottom: 30px;
    }

    .step-text ul {
        list-style: none;
        padding: 0;
    }

    .step-text ul li {
        display: flex;
        align-items: flex-start;
        padding: 15px 0;
        font-size: 15px;
        color: #475569;
    }

    .step-text ul li::before {
        content: "";
        min-width: 8px;
        height: 8px;
        background: var(--ocean);
        border-radius: 50%;
        margin-top: 8px;
        margin-right: 20px;
        box-shadow: 0 0 15px rgba(37, 99, 235, 0.5);
    }

    .video-frame {
        position: relative;
        border-radius: 40px;
        overflow: hidden;
        padding-top: 56.25%;
        background: #000;
        box-shadow: 0 60px 100px -30px rgba(0,0,0,0.3);
    }

    .video-frame iframe {
        position: absolute;
        top: 0; left: 0; width: 100%; height: 100%; border: 0;
    }

    .yt-shortcut {
        display: inline-block;
        margin-top: 25px;
        color: var(--ocean);
        text-decoration: none;
        font-size: 13px;
        font-weight: 700;
        text-transform: uppercase;
        letter-spacing: 1px;
    }

    /* BACK FOOTER - Dashboard Area */
    .back-footer {
        text-align: center;
        padding: 120px 0 160px;
    }

    /* TOMBOL DASHBOARD LEVEL DEWA */
    .btn-dashboard {
        display: inline-flex;
        align-items: center;
        gap: 18px;
        background: linear-gradient(135deg, #0f172a 0%, #2563eb 100%);
        color: #ffffff !important;
        text-decoration: none;
        font-family: 'Plus Jakarta Sans', sans-serif;
        font-weight: 700;
        font-size: 18px;
        padding: 22px 65px;
        border-radius: 100px;
        box-shadow: 0 25px 50px rgba(37, 99, 235, 0.35);
        transition: all 0.5s cubic-bezier(0.175, 0.885, 0.32, 1.275);
        border: none;
    }

    .btn-dashboard:hover {
        transform: translateY(-10px) scale(1.05);
        box-shadow: 0 35px 70px rgba(37, 99, 235, 0.5);
        background: linear-gradient(135deg, #1e293b 0%, #3b82f6 100%);
    }

    .btn-dashboard svg {
        transition: transform 0.4s ease;
    }

    .btn-dashboard:hover svg {
        transform: translateX(-8px);
    }

    @media (max-width: 1024px) {
        .step-grid, .step-grid.reverse { grid-template-columns: 1fr; gap: 50px; }
        .jkn-luxury-wrapper { padding: 60px 30px; }
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
                    <li>Klik menu <strong>Daftar Baru</strong></li>
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
        <div class="step-grid reverse">
            <div class="video-frame">
                <iframe src="https://www.youtube.com/embed/sJ4f2V7uU-A" allowfullscreen></iframe>
            </div>
            <div class="step-text">
                <h4>Pendaftaran Online di Mobile JKN</h4>
                <ul>
                    <li>Login menggunakan akun terdaftar</li>
                    <li>Pilih menu utama <strong>Pendaftaran Pelayanan</strong></li>
                    <li>Cari Fasilitas Kesehatan & Poli tujuan</li>
                    <li>Tentukan jadwal dokter dan tanggal</li>
                    <li>Konfirmasi dan dapatkan nomor antrian</li>
                </ul>
                <a href="https://www.youtube.com/watch?v=sJ4f2V7uU-A" target="_blank" class="yt-shortcut">Video Panduan →</a>
            </div>
        </div>

        {{-- KEMBALI KE DASHBOARD (SUPER AESTHETIC) --}}
        <div class="back-footer">
            <a href="{{ route('pendaftaran.online') }}" class="btn-dashboard">
                <svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="3" stroke-linecap="round" stroke-linejoin="round">
                    <line x1="19" y1="12" x2="5" y2="12"></line>
                    <polyline points="12 19 5 12 12 5"></polyline>
                </svg>
                Kembali ke Dashboard
            </a>
        </div>

    </div>
</div>

@endsection