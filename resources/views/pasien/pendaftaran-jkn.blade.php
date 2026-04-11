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

    /* FULL SCREEN BACKGROUND */
    .ultra-canvas {
        background: radial-gradient(at 0% 0%, rgba(224, 242, 254, 0.5) 0, transparent 50%), 
                    radial-gradient(at 100% 100%, rgba(254, 243, 199, 0.3) 0, transparent 50%);
        min-height: 100vh;
        width: 100%;
        display: flex;
        flex-direction: column;
    }

    /* WRAPPER MELEBAR MENTOK ATAS */
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

    /* HEADER - TYPOGRAPHY KECIL & ELEGAN */
    .jkn-header {
        text-align: center;
        margin-bottom: 90px;
    }

    .jkn-header h2 {
        font-family: 'Plus Jakarta Sans', sans-serif;
        font-size: 42px; /* Diperkecil dari sebelumnya */
        font-weight: 800;
        letter-spacing: -2px;
        color: var(--deep-slate);
        margin-bottom: 12px;
    }

    .jkn-header p {
        font-size: 16px; /* Font size ideal untuk sub-header */
        color: var(--soft-text);
        letter-spacing: 0.2px;
        max-width: 500px;
        margin: 0 auto;
        line-height: 1.6;
    }

    /* PRIMARY CTA */
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
        box-shadow: 0 30px 60px rgba(0,0,0,0.2);
    }

    /* TUTORIAL GRID */
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
        letter-spacing: -0.5px;
    }

    /* POINT TUTORIAL - AESTHETIC DOTS */
    .step-text ul {
        list-style: none;
        padding: 0;
    }

    .step-text ul li {
        display: flex;
        align-items: flex-start;
        padding: 15px 0;
        font-size: 15px; /* Font size diperkecil supaya pro */
        color: #475569;
        line-height: 1.6;
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

    /* VIDEO FRAME LUXURY */
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

    /* BACK BUTTON - SUPER AESTHETIC */
    .back-footer {
        text-align: center;
        padding: 100px 0 140px;
    }

    .btn-dashboard {
        display: inline-flex;
        align-items: center;
        gap: 15px;
        color: var(--deep-slate);
        text-decoration: none;
        font-weight: 600;
        font-size: 14px;
        padding: 15px 35px;
        border-radius: 100px;
        background: rgba(255, 255, 255, 0.5);
        border: 1px solid rgba(0,0,0,0.05);
        backdrop-filter: blur(10px);
        transition: 0.4s cubic-bezier(0.19, 1, 0.22, 1);
    }

    .btn-dashboard:hover {
        background: #fff;
        transform: scale(1.05);
        box-shadow: 0 20px 40px rgba(0,0,0,0.05);
        color: var(--ocean);
    }

    .btn-dashboard svg {
        transition: 0.3s;
    }

    .btn-dashboard:hover svg {
        transform: translateX(-5px);
    }

    /* RESPONSIVE */
    @media (max-width: 1024px) {
        .step-grid, .step-grid.reverse { grid-template-columns: 1fr; gap: 50px; }
        .jkn-luxury-wrapper { padding: 60px 30px; }
    }
</style>

<div class="ultra-canvas">
    <div class="jkn-luxury-wrapper">

        <div class="jkn-header">
            <h2>Portal JKN</h2>
            <p>Sistem pendaftaran rumah sakit terpadu dalam satu genggaman tangan Anda.</p>
        </div>

        <div class="download-area">
            <a href="https://play.google.com/store/apps/details?id=app.bpjs.mobile" target="_blank" class="btn-luxury">
                <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><path d="M21 15v4a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2v-4M7 10l5 5 5-5M12 15V3"/></svg>
                Dapatkan Aplikasi
            </a>
        </div>

        <div class="step-grid">
            <div class="step-text">
                <h4>Aktivasi Pengguna</h4>
                <ul>
                    <li>Siapkan kartu JKN-KIS atau NIK KTP Anda</li>
                    <li>Lengkapi detail pendaftaran akun baru</li>
                    <li>Masukan kode verifikasi unik yang Anda terima</li>
                    <li>Gunakan kata sandi dengan keamanan berlapis</li>
                </ul>
                <a href="https://youtube.com/shorts/ddc21BfzVwQ" target="_blank" class="yt-shortcut">Video Panduan →</a>
            </div>
            <div class="video-frame">
                <iframe src="https://www.youtube.com/embed/ddc21BfzVwQ" allowfullscreen></iframe>
            </div>
        </div>

        <div class="step-grid reverse">
            <div class="video-frame">
                <iframe src="https://www.youtube.com/embed/sJ4f2V7uU-A" allowfullscreen></iframe>
            </div>
            <div class="step-text">
                <h4>Antrian Pelayanan</h4>
                <ul>
                    <li>Pilih menu pendaftaran pelayanan di dashboard</li>
                    <li>Tentukan poli spesialis & tanggal kunjungan</li>
                    <li>Konfirmasi slot waktu yang tersedia</li>
                    <li>Simpan tiket antrian digital Anda</li>
                </ul>
                <a href="https://www.youtube.com/watch?v=sJ4f2V7uU-A" target="_blank" class="yt-shortcut">Video Panduan →</a>
            </div>
        </div>

        <div class="back-footer">
            <a href="{{ route('pendaftaran.online') }}" class="btn-dashboard">
                <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><line x1="19" y1="12" x2="5" y2="12"></line><polyline points="12 19 5 12 12 5"></polyline></svg>
                Kembali ke Dashboard
            </a>
        </div>

    </div>
</div>

@endsection