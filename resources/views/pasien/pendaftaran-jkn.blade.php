@extends('layout.app')

@section('content')

<link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@300;400;600;700;800&display=swap" rel="stylesheet">

<style>
    :root {
        --primary: #2563eb;
        --primary-dark: #1e40af;
        --bg-gradient: linear-gradient(135deg, #f0f9ff 0%, #e0f2fe 100%);
        --glass: rgba(255, 255, 255, 0.85);
        --text-main: #1e293b;
        --text-sub: #64748b;
    }

    body {
        background: var(--bg-gradient);
        font-family: 'Plus Jakarta Sans', sans-serif;
        color: var(--text-main);
    }

    /* CONTAINER UTAMA */
    .jkn-wrapper {
        max-width: 900px;
        margin: 60px auto;
        padding: 60px 40px;
        background: var(--glass);
        backdrop-filter: blur(20px);
        border-radius: 40px;
        border: 1px solid rgba(255, 255, 255, 0.5);
        box-shadow: 0 40px 100px rgba(0, 0, 0, 0.05);
    }

    /* HEADER */
    .jkn-header {
        text-align: center;
        margin-bottom: 60px;
    }

    .jkn-header h2 {
        font-size: 42px;
        font-weight: 800;
        letter-spacing: -1px;
        background: linear-gradient(to right, #1e3a8a, #2563eb);
        -webkit-background-clip: text;
        -webkit-text-fill-color: transparent;
        margin-bottom: 15px;
    }

    .jkn-header p {
        font-size: 18px;
        color: var(--text-sub);
        line-height: 1.6;
        max-width: 650px;
        margin: 0 auto;
    }

    /* DOWNLOAD BUTTON */
    .download-section {
        text-align: center;
        margin-bottom: 60px;
    }

    .btn-medical {
        display: inline-flex;
        align-items: center;
        gap: 12px;
        background: #1e293b;
        color: #fff;
        padding: 18px 40px;
        border-radius: 20px;
        font-size: 16px;
        font-weight: 700;
        text-decoration: none;
        transition: all 0.4s cubic-bezier(0.175, 0.885, 0.32, 1.275);
        box-shadow: 0 20px 40px rgba(0, 0, 0, 0.1);
    }

    .btn-medical:hover {
        transform: translateY(-5px) scale(1.02);
        background: #0f172a;
        box-shadow: 0 25px 50px rgba(0, 0, 0, 0.2);
    }

    /* TUTORIAL CARD */
    .tutorial-card {
        margin-top: 40px;
        padding: 45px;
        border-radius: 35px;
        background: #ffffff;
        border: 1px solid #f1f5f9;
        box-shadow: 0 10px 30px rgba(0, 0, 0, 0.02);
        transition: 0.3s;
    }

    .tutorial-card:hover {
        box-shadow: 0 30px 60px rgba(0, 0, 0, 0.05);
    }

    .tutorial-card h4 {
        font-size: 24px;
        font-weight: 800;
        color: #1e3a8a;
        margin-bottom: 25px;
        display: flex;
        align-items: center;
        gap: 15px;
    }

    .tutorial-card ul {
        list-style: none;
        padding: 0;
        margin-bottom: 35px;
    }

    .tutorial-card ul li {
        padding: 12px 0;
        font-size: 16px;
        color: #475569;
        border-bottom: 1px solid #f8fafc;
        display: flex;
        align-items: center;
    }

    .tutorial-card ul li::before {
        content: "✦";
        color: var(--primary);
        margin-right: 15px;
        font-weight: bold;
    }

    /* VIDEO STYLE */
    .video-container {
        position: relative;
        margin-top: 30px;
        border-radius: 25px;
        overflow: hidden;
        padding-top: 56.25%; /* Aspect Ratio 16:9 */
        background: #000;
        box-shadow: 0 25px 50px -12px rgba(0, 0, 0, 0.15);
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
        margin-top: 20px;
        color: var(--primary);
        text-decoration: none;
        font-size: 15px;
        font-weight: 700;
        transition: 0.3s;
    }

    .watch-on-yt:hover {
        gap: 12px;
        color: var(--primary-dark);
    }

    /* BACK BUTTON */
    .back-section {
        text-align: center;
        margin-top: 80px;
    }

    .btn-back {
        background: transparent;
        color: #64748b;
        padding: 15px 35px;
        border-radius: 15px;
        text-decoration: none;
        font-weight: 700;
        border: 2px solid #e2e8f0;
        transition: all 0.3s;
    }

    .btn-back:hover {
        background: #f1f5f9;
        border-color: #cbd5e1;
        color: #1e293b;
    }
</style>

<div class="jkn-wrapper">

    {{-- HEADER --}}
    <div class="jkn-header">
        <h2>Layanan JKN Online</h2>
        <p>
            Solusi praktis pendaftaran rumah sakit langsung dari genggaman Anda. 
            Simak panduan eksklusif di bawah ini.
        </p>
    </div>

    {{-- DOWNLOAD BUTTON --}}
    <div class="download-section">
        <a href="https://play.google.com/store/apps/details?id=app.bpjs.mobile"
           target="_blank"
           class="btn-medical">
           <svg width="24" height="24" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                <path d="M12 15L12 3M12 15L8 11M12 15L16 11M2 17L2.621 17.828C4.01 19.681 4.704 20.607 5.666 21.303C6.627 22 7.785 22 10.1 22H13.9C16.215 22 17.373 22 18.334 21.303C19.296 20.607 19.99 19.681 21.379 17.828L22 17" stroke="white" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
           </svg>
           Dapatkan Aplikasi Mobile JKN
        </a>
    </div>

    {{-- ===================== TUTORIAL 1 ===================== --}}
    <div class="tutorial-card">
        <h4><span style="font-size: 30px;">🔐</span> Aktivasi Akun</h4>
        <ul>
            <li>Unduh & buka Mobile JKN di ponsel Anda</li>
            <li>Klik menu <strong>Daftar Baru</strong></li>
            <li>Input Nomor Kartu BPJS / NIK KTP sesuai identitas</li>
            <li>Lakukan verifikasi melalui OTP yang dikirimkan</li>
            <li>Tentukan password keamanan Anda</li>
        </ul>

        <div class="video-container">
            <iframe 
                src="https://www.youtube.com/embed/ddc21BfzVwQ"
                title="Tutorial Daftar Akun Mobile JKN"
                allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture"
                allowfullscreen>
            </iframe>
        </div>
        
        <a href="https://youtube.com/shorts/ddc21BfzVwQ" target="_blank" class="watch-on-yt">
            Buka di Aplikasi YouTube 
            <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><path d="M5 12h14M12 5l7 7-7 7"/></svg>
        </a>
    </div>

    {{-- ===================== TUTORIAL 2 ===================== --}}
    <div class="tutorial-card">
        <h4><span style="font-size: 30px;">🗓️</span> Booking Antrian Online</h4>
        <ul>
            <li>Login ke aplikasi menggunakan akun terdaftar</li>
            <li>Pilih menu utama <strong>Pendaftaran Pelayanan</strong></li>
            <li>Cari Fasilitas Kesehatan & Poli yang dituju</li>
            <li>Pilih jadwal dokter dan tanggal kunjungan</li>
            <li>Konfirmasi dan dapatkan nomor antrian digital</li>
        </ul>

        <div class="video-container">
            <iframe 
                src="https://www.youtube.com/embed/sJ4f2V7uU-A"
                title="Tutorial Pendaftaran Online Mobile JKN"
                allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture"
                allowfullscreen>
            </iframe>
        </div>

        <a href="https://www.youtube.com/watch?v=sJ4f2V7uU-A" target="_blank" class="watch-on-yt">
            Buka di Aplikasi YouTube
            <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><path d="M5 12h14M12 5l7 7-7 7"/></svg>
        </a>
    </div>

    {{-- BACK BUTTON --}}
    <div class="back-section">
        <a href="{{ route('pendaftaran.online') }}" class="btn-back">
            Kembali ke Dashboard
        </a>
    </div>

</div>

@endsection