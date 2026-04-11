@extends('layout.app')

@section('content')

<style>
    /* IMPORT FONT (Opsional jika belum ada di app.blade) */
    @import url('https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;600;700&display=swap');

    body {
        background: radial-gradient(circle at top right, #eef5ff, #f0f7ff);
        font-family: 'Plus Jakarta Sans', sans-serif;
        color: #334155;
    }

    /* CONTAINER UTAMA */
    .jkn-wrapper {
        max-width: 900px;
        margin: 60px auto;
        padding: 40px;
        background: rgba(255, 255, 255, 0.8);
        backdrop-filter: blur(10px); /* Efek Kaca */
        border-radius: 32px;
        border: 1px solid rgba(255, 255, 255, 0.4);
        box-shadow: 0 25px 50px -12px rgba(0, 0, 0, 0.05);
    }

    /* HEADER */
    .jkn-header {
        text-align: center;
        margin-bottom: 40px;
    }

    .jkn-header h2 {
        font-size: 36px;
        font-weight: 800;
        background: linear-gradient(135deg, #1e3a8a 0%, #2563eb 100%);
        -webkit-background-clip: text;
        -webkit-text-fill-color: transparent;
        margin-bottom: 15px;
    }

    .jkn-header p {
        font-size: 17px;
        color: #64748b;
        max-width: 600px;
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
        gap: 10px;
        background: #1e293b; /* Dark sleek color */
        color: #fff;
        padding: 18px 45px;
        border-radius: 20px;
        font-size: 16px;
        font-weight: 600;
        text-decoration: none;
        transition: all 0.4s cubic-bezier(0.175, 0.885, 0.32, 1.275);
        box-shadow: 0 10px 25px rgba(0,0,0,0.1);
    }

    .btn-medical:hover {
        transform: translateY(-5px) scale(1.02);
        background: #0f172a;
        box-shadow: 0 20px 30px rgba(0,0,0,0.15);
    }

    /* TUTORIAL CARD */
    .tutorial-card {
        margin-top: 40px;
        padding: 40px;
        border-radius: 28px;
        background: #ffffff;
        border: 1px solid #f1f5f9;
        position: relative;
        transition: transform 0.3s ease;
    }

    .tutorial-card:hover {
        border-color: #dbeafe;
    }

    .tutorial-card h4 {
        font-size: 22px;
        font-weight: 700;
        color: #1e3a8a;
        margin-bottom: 25px;
        display: flex;
        align-items: center;
        gap: 12px;
    }

    .tutorial-card ul {
        list-style: none;
        padding: 0;
        margin-bottom: 30px;
    }

    .tutorial-card ul li {
        position: relative;
        padding-left: 30px;
        margin-bottom: 12px;
        font-size: 15px;
        color: #475569;
    }

    .tutorial-card ul li::before {
        content: '✓';
        position: absolute;
        left: 0;
        color: #10b981;
        font-weight: bold;
    }

    /* VIDEO CONTAINER (Thumbnail Style) */
    .video-container {
        position: relative;
        border-radius: 24px;
        overflow: hidden;
        cursor: pointer;
        background: #000;
        box-shadow: 0 20px 40px rgba(0,0,0,0.1);
    }

    .video-thumbnail {
        width: 100%;
        height: 450px;
        object-fit: cover;
        display: block;
        opacity: 0.85;
        transition: all 0.5s ease;
    }

    .video-container:hover .video-thumbnail {
        transform: scale(1.05);
        opacity: 0.7;
    }

    .video-overlay {
        position: absolute;
        top: 0;
        left: 0;
        width: 100%;
        height: 100%;
        display: flex;
        flex-direction: column;
        align-items: center;
        justify-content: center;
        background: rgba(30, 58, 138, 0.2);
        transition: all 0.3s ease;
    }

    .play-icon {
        width: 80px;
        height: 80px;
        background: rgba(255, 255, 255, 0.9);
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        margin-bottom: 15px;
        box-shadow: 0 0 30px rgba(255,255,255,0.4);
        transition: all 0.3s ease;
    }

    .play-icon svg {
        width: 30px;
        height: 30px;
        fill: #1e3a8a;
        margin-left: 5px;
    }

    .video-container:hover .play-icon {
        transform: scale(1.2);
        background: #ffffff;
    }

    .watch-label {
        color: #fff;
        font-weight: 700;
        font-size: 14px;
        letter-spacing: 1px;
        text-transform: uppercase;
        background: rgba(0,0,0,0.3);
        padding: 8px 20px;
        border-radius: 50px;
        backdrop-filter: blur(5px);
    }

    /* BACK BUTTON */
    .back-section {
        text-align: center;
        margin-top: 60px;
    }

    .btn-back {
        color: #64748b;
        text-decoration: none;
        font-weight: 600;
        font-size: 15px;
        display: inline-flex;
        align-items: center;
        gap: 8px;
        transition: color 0.3s;
    }

    .btn-back:hover {
        color: #1e293b;
    }
</style>

<div class="jkn-wrapper">

    {{-- HEADER --}}
    <div class="jkn-header">
        <h2>Layanan JKN Online</h2>
        <p>Nikmati kemudahan pendaftaran rumah sakit langsung dari ponsel Anda melalui sistem integrasi Mobile JKN.</p>
    </div>

    {{-- DOWNLOAD BUTTON --}}
    <div class="download-section">
        <a href="https://play.google.com/store/apps/details?id=app.bpjs.mobile" target="_blank" class="btn-medical">
            <svg style="width:20px;height:20px" viewBox="0 0 24 24"><path fill="currentColor" d="M17,18H7V6H17M17,1H7C5.89,1 5,1.89 5,3V21C5,22.11 5.89,23 7,23H17C18.11,23 19,22.11 19,21V3C19,1.89 18.11,1 17,1Z"/></svg>
            Buka Aplikasi Mobile JKN
        </a>
    </div>

    {{-- TUTORIAL 1 --}}
    <div class="tutorial-card">
        <h4><span>📝</span> Registrasi & Aktivasi Akun</h4>
        <ul>
            <li>Siapkan Nomor Kartu JKN atau NIK KTP Anda.</li>
            <li>Klik tombol <strong>Daftar</strong> pada halaman utama aplikasi.</li>
            <li>Masukkan data diri secara lengkap dan valid.</li>
            <li>Verifikasi email/nomor HP melalui kode OTP.</li>
        </ul>

        <a href="https://www.youtube.com/watch?v=ddc21BfzVwQ" target="_blank" class="video-container">
            <img src="https://img.youtube.com/vi/ddc21BfzVwQ/maxresdefault.jpg" class="video-thumbnail" alt="Tutorial Registrasi">
            <div class="video-overlay">
                <div class="play-icon">
                    <svg viewBox="0 0 24 24"><path d="M8,5.14V19.14L19,12.14L8,5.14Z"/></svg>
                </div>
                <div class="watch-label">Putar Tutorial</div>
            </div>
        </a>
    </div>

    {{-- TUTORIAL 2 --}}
    <div class="tutorial-card">
        <h4><span>📅</span> Pendaftaran Antrian Online</h4>
        <ul>
            <li>Masuk ke aplikasi menggunakan akun yang telah aktif.</li>
            <li>Pilih menu <strong>Pendaftaran Pelayanan</strong> (Antrian).</li>
            <li>Pilih Fasilitas Kesehatan dan Poli yang dituju.</li>
            <li>Ambil nomor antrian digital untuk hari kunjungan.</li>
        </ul>

        <a href="https://www.youtube.com/watch?v=sJ4f2V7uU-A" target="_blank" class="video-container">
            <img src="https://img.youtube.com/vi/sJ4f2V7uU-A/maxresdefault.jpg" class="video-thumbnail" alt="Tutorial Antrian">
            <div class="video-overlay">
                <div class="play-icon">
                    <svg viewBox="0 0 24 24"><path d="M8,5.14V19.14L19,12.14L8,5.14Z"/></svg>
                </div>
                <div class="watch-label">Putar Tutorial</div>
            </div>
        </a>
    </div>

    {{-- BACK SECTION --}}
    <div class="back-section">
        <a href="{{ route('pendaftaran.online') }}" class="btn-back">
            ← Kembali ke Menu Utama
        </a>
    </div>

</div>

@endsection