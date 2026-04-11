@extends('layout.app')

@section('content')

<link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500&family=Plus+Jakarta+Sans:wght@600;700;800&display=swap" rel="stylesheet">

<style>
    :root {
        --primary: #0f172a; /* Deep Sleek Indigo */
        --accent: #2563eb; /* Med-Blue */
        --soft-bg: #f8fafc;
        --card-bg: rgba(255, 255, 255, 0.95);
        --text-dark: #0f172a;
        --text-muted: #64748b;
    }

    body {
        background: radial-gradient(circle at top left, #f0f9ff, #e0f2fe, #fdfcfb);
        font-family: 'Inter', sans-serif;
        color: var(--text-dark);
        margin: 0;
        padding-bottom: 50px;
    }

    /* CONTAINER UTAMA */
    .jkn-wrapper {
        max-width: 850px;
        margin: 80px auto;
        padding: 70px 50px;
        background: var(--card-bg);
        border-radius: 48px;
        border: 1px solid rgba(255, 255, 255, 0.8);
        box-shadow: 
            0 10px 1px rgba(0, 0, 0, 0.01),
            0 25px 50px -12px rgba(0, 0, 0, 0.08);
        position: relative;
    }

    /* HEADER */
    .jkn-header {
        text-align: center;
        margin-bottom: 70px;
    }

    .jkn-header h2 {
        font-family: 'Plus Jakarta Sans', sans-serif;
        font-size: 40px;
        font-weight: 800;
        letter-spacing: -1.5px;
        color: var(--text-dark);
        margin-bottom: 18px;
    }

    .jkn-header p {
        font-size: 17px;
        color: var(--text-muted);
        line-height: 1.7;
        max-width: 580px;
        margin: 0 auto;
        font-weight: 400;
    }

    /* DOWNLOAD BUTTON */
    .download-section {
        text-align: center;
        margin-bottom: 80px;
    }

    .btn-medical {
        display: inline-flex;
        align-items: center;
        gap: 12px;
        background: var(--primary);
        color: #fff;
        padding: 20px 48px;
        border-radius: 24px;
        font-size: 16px;
        font-weight: 700;
        text-decoration: none;
        transition: 0.4s cubic-bezier(0.2, 1, 0.3, 1);
        box-shadow: 0 15px 30px rgba(15, 23, 42, 0.25);
    }

    .btn-medical:hover {
        transform: translateY(-4px);
        box-shadow: 0 25px 45px rgba(15, 23, 42, 0.35);
    }

    /* TUTORIAL CARD */
    .tutorial-card {
        margin-top: 50px;
        padding: 55px;
        border-radius: 40px;
        background: #ffffff;
        border: 1px solid #f1f5f9;
        box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.02);
    }

    .tutorial-card h4 {
        font-family: 'Plus Jakarta Sans', sans-serif;
        font-size: 24px;
        font-weight: 700;
        color: var(--text-dark);
        margin-bottom: 35px;
        display: flex;
        align-items: center;
        gap: 15px;
    }

    /* LIST STYLE (Gak AI Banget - Numbered Pill) */
    .tutorial-card ul {
        list-style: none;
        padding: 0;
        margin-bottom: 40px;
        counter-reset: step-counter;
    }

    .tutorial-card ul li {
        padding: 14px 0;
        font-size: 16px;
        color: #334155;
        display: flex;
        align-items: center;
        border-bottom: 1px solid #f8fafc;
    }

    .tutorial-card ul li::before {
        counter-increment: step-counter;
        content: counter(step-counter);
        background: #f1f5f9;
        color: #475569;
        min-width: 28px;
        height: 28px;
        border-radius: 10px;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 12px;
        font-weight: 800;
        margin-right: 20px;
        transition: 0.3s;
    }

    .tutorial-card:hover ul li::before {
        background: var(--accent);
        color: #fff;
    }

    /* VIDEO STYLE */
    .video-container {
        position: relative;
        border-radius: 32px;
        overflow: hidden;
        padding-top: 56.25%;
        background: #000;
        box-shadow: 0 30px 60px -15px rgba(0, 0, 0, 0.2);
    }

    .video-container iframe {
        position: absolute;
        top: 0;
        left: 0;
        width: 100%;
        height: 100%;
        border: 0;
    }

    .watch-on-yt {
        display: inline-flex;
        align-items: center;
        gap: 8px;
        margin-top: 25px;
        color: var(--accent);
        text-decoration: none;
        font-size: 15px;
        font-weight: 600;
        transition: 0.2s;
    }

    .watch-on-yt:hover {
        color: var(--primary);
        text-decoration: underline;
    }

    /* BACK BUTTON */
    .back-section {
        text-align: center;
        margin-top: 90px;
    }

    .btn-back {
        background: transparent;
        color: var(--text-muted);
        padding: 16px 40px;
        border-radius: 20px;
        text-decoration: none;
        font-weight: 600;
        font-size: 15px;
        border: 1.5px solid #e2e8f0;
        transition: 0.3s;
    }

    .btn-back:hover {
        background: #fff;
        border-color: var(--text-dark);
        color: var(--text-dark);
    }
</style>

<div class="jkn-wrapper">

    {{-- HEADER --}}
    <div class="jkn-header">
        <h2>Panduan Mobile JKN</h2>
        <p>
            Kelola pendaftaran rumah sakit dengan lebih tenang dan teratur. 
            Ikuti langkah-langkah di bawah ini.
        </p>
    </div>

    {{-- DOWNLOAD BUTTON --}}
    <div class="download-section">
        <a href="https://play.google.com/store/apps/details?id=app.bpjs.mobile"
           target="_blank"
           class="btn-medical">
           <svg width="22" height="22" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                <path d="M12 15V3M12 15L8 11M12 15L16 11M2 17L2.62 17.83C4.01 19.68 4.7 20.61 5.67 21.3C6.63 22 7.79 22 10.1 22H13.9C16.22 22 17.37 22 18.33 21.3C19.3 20.61 19.99 19.68 21.38 17.83L22 17" stroke="white" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"/>
           </svg>
           Instal Aplikasi Mobile JKN
        </a>
    </div>

    {{-- ===================== TUTORIAL 1 ===================== --}}
    <div class="tutorial-card">
        <h4>🔐 Pembuatan Akun Baru</h4>
        <ul>
            <li>Unduh aplikasi resmi melalui Store ponsel Anda</li>
            <li>Pilih menu <strong>Pendaftaran Pengguna Mobile</strong></li>
            <li>Siapkan NIK dan Nomor Kartu JKN Anda</li>
            <li>Masukan kode OTP yang dikirimkan ke nomor ponsel</li>
            <li>Simpan password dengan kombinasi yang kuat</li>
        </ul>

        <div class="video-container">
            <iframe 
                src="https://www.youtube.com/embed/ddc21BfzVwQ"
                title="Tutorial Daftar Akun"
                allowfullscreen>
            </iframe>
        </div>
        
        <a href="https://youtube.com/shorts/ddc21BfzVwQ" target="_blank" class="watch-on-yt">
            Lihat detail di YouTube →
        </a>
    </div>

    {{-- ===================== TUTORIAL 2 ===================== --}}
    <div class="tutorial-card" style="margin-top: 60px;">
        <h4>🗓️ Pendaftaran Antrian Online</h4>
        <ul>
            <li>Login ke aplikasi menggunakan akun yang aktif</li>
            <li>Cari dan pilih menu <strong>Pendaftaran Pelayanan</strong></li>
            <li>Tentukan Fasilitas Kesehatan tujuan (Rujukan)</li>
            <li>Pilih Poli Spesialis dan tanggal kunjungan</li>
            <li>Simpan bukti antrian untuk ditunjukkan ke petugas</li>
        </ul>

        <div class="video-container">
            <iframe 
                src="https://www.youtube.com/embed/sJ4f2V7uU-A"
                title="Tutorial Antrian Online"
                allowfullscreen>
            </iframe>
        </div>

        <a href="https://www.youtube.com/watch?v=sJ4f2V7uU-A" target="_blank" class="watch-on-yt">
            Lihat detail di YouTube →
        </a>
    </div>

    {{-- BACK BUTTON --}}
    <div class="back-section">
        <a href="{{ route('pendaftaran.online') }}" class="btn-back">
            Kembali ke Menu Utama
        </a>
    </div>

</div>

@endsection