@extends('layout.app')

@section('content')

<link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap" rel="stylesheet">

<style>
    /* RESET & BASE */
    :root {
        --primary: #0f172a;
        --accent: #3b82f6;
        --accent-soft: rgba(59, 130, 246, 0.1);
        --text-main: #1e293b;
        --text-muted: #64748b;
    }

    body, html {
        margin: 0 !important;
        padding: 0 !important;
        width: 100%;
        min-height: 100vh;
        overflow-x: hidden;
    }

    /* FULL BACKGROUND GRADIENT */
    .master-container {
        background: linear-gradient(135deg, #f8fafc 0%, #e2e8f0 40%, #dbeafe 100%);
        min-height: 100vh;
        width: 100%;
        display: flex;
        flex-direction: column;
        position: relative;
    }

    /* DECORATIVE ORBS (Efek Estetik di Background) */
    .master-container::before {
        content: '';
        position: absolute;
        top: -10%; left: -5%;
        width: 400px; height: 400px;
        background: radial-gradient(circle, rgba(59, 130, 246, 0.15) 0%, transparent 70%);
        z-index: 0;
    }

    /* WRAPPER MELEBAR MENTOK ATAS */
    .jkn-wrapper {
        max-width: 1400px;
        width: 92%;
        margin: 0 auto;
        padding: 100px 60px;
        background: rgba(255, 255, 255, 0.6);
        backdrop-filter: blur(40px);
        -webkit-backdrop-filter: blur(40px);
        border-radius: 0 0 80px 80px;
        box-shadow: 0 50px 100px -20px rgba(0, 0, 0, 0.04);
        border: 1px solid rgba(255, 255, 255, 0.7);
        border-top: none;
        position: relative;
        z-index: 1;
    }

    /* HEADER */
    .jkn-header {
        text-align: center;
        margin-bottom: 80px;
    }

    .jkn-header h2 {
        font-family: 'Plus Jakarta Sans', sans-serif;
        font-size: clamp(36px, 6vw, 64px);
        font-weight: 800;
        letter-spacing: -3px;
        color: var(--primary);
        line-height: 1;
        margin-bottom: 25px;
    }

    .jkn-header p {
        font-size: 20px;
        color: var(--text-muted);
        max-width: 700px;
        margin: 0 auto;
        font-weight: 500;
    }

    /* DOWNLOAD BUTTON SLEEK */
    .download-section {
        text-align: center;
        margin-bottom: 100px;
    }

    .btn-medical {
        display: inline-flex;
        align-items: center;
        gap: 15px;
        background: var(--primary);
        color: #fff;
        padding: 24px 60px;
        border-radius: 30px;
        font-size: 18px;
        font-weight: 700;
        text-decoration: none;
        transition: all 0.5s cubic-bezier(0.19, 1, 0.22, 1);
        box-shadow: 0 30px 60px -15px rgba(15, 23, 42, 0.4);
    }

    .btn-medical:hover {
        transform: translateY(-8px);
        box-shadow: 0 40px 80px -20px rgba(15, 23, 42, 0.5);
        background: #000;
    }

    /* GRID TUTORIAL */
    .tutorial-grid {
        display: grid;
        grid-template-columns: 1fr 1.4fr;
        gap: 100px;
        align-items: center;
        margin-bottom: 120px;
        padding: 60px;
        background: rgba(255, 255, 255, 0.4);
        border-radius: 50px;
        border: 1px solid rgba(255, 255, 255, 0.8);
    }

    .tutorial-grid.reverse { grid-template-columns: 1.4fr 1fr; }

    .tutorial-content h4 {
        font-family: 'Plus Jakarta Sans', sans-serif;
        font-size: 36px;
        font-weight: 800;
        color: var(--primary);
        margin-bottom: 35px;
        letter-spacing: -1px;
    }

    /* POINT TUTORIAL (AESTHETIC PARAH) */
    .tutorial-content ul {
        list-style: none;
        padding: 0;
        counter-reset: my-step;
    }

    .tutorial-content ul li {
        counter-increment: my-step;
        display: flex;
        align-items: center;
        padding: 22px 0;
        font-size: 19px;
        color: var(--text-main);
        font-weight: 500;
        transition: 0.3s;
    }

    .tutorial-content ul li::before {
        content: "0" counter(my-step);
        background: white;
        color: var(--accent);
        min-width: 50px;
        height: 50px;
        border-radius: 18px;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 14px;
        font-weight: 800;
        margin-right: 25px;
        box-shadow: 0 10px 20px rgba(0,0,0,0.03);
        border: 1px solid #f1f5f9;
        transition: all 0.4s ease;
    }

    .tutorial-grid:hover ul li::before {
        background: var(--accent);
        color: white;
        transform: scale(1.1) rotate(-5deg);
    }

    /* VIDEO BOX */
    .video-box {
        position: relative;
        border-radius: 50px;
        overflow: hidden;
        padding-top: 56.25%;
        background: #000;
        box-shadow: 0 50px 100px -30px rgba(0, 0, 0, 0.35);
    }

    .video-box iframe {
        position: absolute;
        top: 0;
        left: 0;
        width: 100%;
        height: 100%;
        border: 0;
    }

    .watch-yt-link {
        display: inline-flex;
        margin-top: 30px;
        color: var(--accent);
        text-decoration: none;
        font-weight: 800;
        font-size: 16px;
        letter-spacing: 0.5px;
        transition: 0.3s;
    }

    .watch-yt-link:hover { gap: 10px; opacity: 0.8; }

    /* KEMBALI KE DASHBOARD (SUPER AESTHETIC) */
    .back-section {
        text-align: center;
        padding: 120px 0 150px 0; /* Mentok ke bawah dengan lega */
    }

    .btn-back-dashboard {
        display: inline-flex;
        align-items: center;
        gap: 12px;
        color: var(--text-muted);
        text-decoration: none;
        font-weight: 700;
        font-size: 16px;
        padding: 20px 45px;
        border-radius: 100px;
        background: white;
        border: 2px solid #f1f5f9;
        transition: all 0.4s ease;
        box-shadow: 0 10px 30px rgba(0,0,0,0.02);
    }

    .btn-back-dashboard:hover {
        background: var(--primary);
        color: white;
        border-color: var(--primary);
        transform: translateX(-10px);
        box-shadow: 0 20px 40px rgba(0,0,0,0.1);
    }

    .btn-back-dashboard svg {
        transition: transform 0.4s ease;
    }

    .btn-back-dashboard:hover svg {
        transform: translateX(-5px);
        stroke: white;
    }

    /* RESPONSIVE */
    @media (max-width: 1200px) {
        .tutorial-grid, .tutorial-grid.reverse {
            grid-template-columns: 1fr;
            padding: 40px;
            gap: 50px;
        }
        .jkn-wrapper { padding: 80px 30px; width: 95%; }
    }
</style>

<div class="master-container">
    <div class="jkn-wrapper">

        <div class="jkn-header">
            <h2>Portal JKN Digital</h2>
            <p>Panduan eksklusif untuk akses kesehatan yang lebih cerdas dan efisien.</p>
        </div>

        <div class="download-section">
            <a href="https://play.google.com/store/apps/details?id=app.bpjs.mobile" target="_blank" class="btn-medical">
                <svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><path d="M21 15v4a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2v-4M7 10l5 5 5-5M12 15V3"/></svg>
                Instal Mobile JKN
            </a>
        </div>

        <div class="tutorial-grid">
            <div class="tutorial-content">
                <h4>🔐 Registrasi User</h4>
                <ul>
                    <li>Gunakan identitas KTP & Nomor JKN valid</li>
                    <li>Lengkapi formulir pendaftaran digital</li>
                    <li>Verifikasi akses via kode OTP rahasia</li>
                    <li>Ciptakan password keamanan tingkat tinggi</li>
                </ul>
                <a href="https://youtube.com/shorts/ddc21BfzVwQ" target="_blank" class="watch-yt-link">WATCH ON YOUTUBE →</a>
            </div>
            <div class="video-box">
                <iframe src="https://www.youtube.com/embed/ddc21BfzVwQ" allowfullscreen></iframe>
            </div>
        </div>

        <div class="tutorial-grid reverse">
            <div class="video-box">
                <iframe src="https://www.youtube.com/embed/sJ4f2V7uU-A" allowfullscreen></iframe>
            </div>
            <div class="tutorial-content">
                <h4>🗓️ Antrian Online</h4>
                <ul>
                    <li>Masuk ke menu Pendaftaran Pelayanan</li>
                    <li>Tentukan Faskes & Poli spesialis tujuan</li>
                    <li>Pilih slot waktu kunjungan yang tersedia</li>
                    <li>Gunakan QR Antrian saat tiba di lokasi</li>
                </ul>
                <a href="https://www.youtube.com/watch?v=sJ4f2V7uU-A" target="_blank" class="watch-yt-link">WATCH ON YOUTUBE →</a>
            </div>
        </div>

        <div class="back-section">
            <a href="{{ route('pendaftaran.online') }}" class="btn-back-dashboard">
                <svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="#64748b" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><line x1="19" y1="12" x2="5" y2="12"></line><polyline points="12 19 5 12 12 5"></polyline></svg>
                Kembali ke Dashboard
            </a>
        </div>

    </div>
</div>

@endsection