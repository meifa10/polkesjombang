@extends('layout.app')

@section('content')

<link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500&family=Plus+Jakarta+Sans:wght@700;800&display=swap" rel="stylesheet">

<style>
    :root {
        --primary: #0f172a;
        --accent: #2563eb;
        --text-muted: #64748b;
        --glass-bg: rgba(255, 255, 255, 0.9);
    }

    body {
        background: radial-gradient(circle at 0% 0%, #f0f9ff 0%, #e0f2fe 50%, #fdfcfb 100%);
        font-family: 'Inter', sans-serif;
        margin: 0;
        padding: 40px 20px;
    }

    /* WRAPPER MELEBAR */
    .jkn-wrapper {
        max-width: 1300px; /* Diperlebar ke samping */
        margin: 0 auto;
        padding: 80px 60px;
        background: var(--glass-bg);
        backdrop-filter: blur(20px);
        border-radius: 50px;
        border: 1px solid rgba(255, 255, 255, 0.7);
        box-shadow: 0 40px 100px -20px rgba(0, 0, 0, 0.05);
    }

    /* HEADER */
    .jkn-header {
        text-align: center;
        margin-bottom: 80px;
    }

    .jkn-header h2 {
        font-family: 'Plus Jakarta Sans', sans-serif;
        font-size: 48px;
        font-weight: 800;
        letter-spacing: -2px;
        color: var(--primary);
        margin-bottom: 20px;
    }

    .jkn-header p {
        font-size: 19px;
        color: var(--text-muted);
        max-width: 700px;
        margin: 0 auto;
    }

    /* BUTTON */
    .download-section {
        text-align: center;
        margin-bottom: 100px;
    }

    .btn-medical {
        display: inline-flex;
        align-items: center;
        gap: 12px;
        background: var(--primary);
        color: #fff;
        padding: 22px 50px;
        border-radius: 25px;
        font-size: 17px;
        font-weight: 700;
        text-decoration: none;
        transition: 0.4s cubic-bezier(0.2, 1, 0.3, 1);
        box-shadow: 0 20px 40px rgba(15, 23, 42, 0.2);
    }

    .btn-medical:hover {
        transform: translateY(-5px);
        box-shadow: 0 30px 60px rgba(15, 23, 42, 0.3);
    }

    /* GRID TUTORIAL (MELEBAR KE SAMPING) */
    .tutorial-grid {
        display: grid;
        grid-template-columns: 1fr 1.2fr; /* Teks kiri, Video kanan */
        gap: 60px;
        align-items: center;
        margin-bottom: 120px;
        padding: 40px;
        background: #fff;
        border-radius: 40px;
        border: 1px solid #f1f5f9;
        transition: 0.3s;
    }

    .tutorial-grid:hover {
        box-shadow: 0 30px 70px rgba(0, 0, 0, 0.04);
    }

    /* Row Reversing untuk selang-seling (Opsional) */
    .tutorial-grid.reverse {
        grid-template-columns: 1.2fr 1fr;
    }

    .tutorial-content h4 {
        font-family: 'Plus Jakarta Sans', sans-serif;
        font-size: 28px;
        font-weight: 700;
        color: var(--primary);
        margin-bottom: 30px;
    }

    .tutorial-content ul {
        list-style: none;
        padding: 0;
        counter-reset: my-counter;
    }

    .tutorial-content ul li {
        counter-increment: my-counter;
        display: flex;
        align-items: center;
        padding: 18px 0;
        font-size: 17px;
        color: #334155;
        border-bottom: 1px solid #f1f5f9;
    }

    .tutorial-content ul li::before {
        content: counter(my-counter);
        background: #f1f5f9;
        min-width: 32px;
        height: 32px;
        border-radius: 12px;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 13px;
        font-weight: 800;
        margin-right: 20px;
        color: var(--text-muted);
    }

    /* VIDEO BOX */
    .video-box {
        position: relative;
        border-radius: 35px;
        overflow: hidden;
        padding-top: 56.25%;
        background: #000;
        box-shadow: 0 40px 80px -20px rgba(0, 0, 0, 0.25);
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
        margin-top: 25px;
        color: var(--accent);
        text-decoration: none;
        font-weight: 700;
        font-size: 15px;
    }

    /* BACK BUTTON */
    .back-section {
        text-align: center;
        margin-top: 100px;
    }

    .btn-back {
        color: var(--text-muted);
        text-decoration: none;
        font-weight: 600;
        padding: 18px 45px;
        border-radius: 20px;
        border: 2px solid #e2e8f0;
        transition: 0.3s;
    }

    .btn-back:hover {
        border-color: var(--primary);
        color: var(--primary);
        background: #fff;
    }

    /* Responsive untuk HP */
    @media (max-width: 1024px) {
        .tutorial-grid, .tutorial-grid.reverse {
            grid-template-columns: 1fr;
            padding: 30px;
        }
        .jkn-wrapper { padding: 40px 20px; }
    }
</style>

<div class="jkn-wrapper">

    <div class="jkn-header">
        <h2>Panduan Mobile JKN</h2>
        <p>Proses registrasi dan pendaftaran antrian kini lebih modern, cepat, dan transparan.</p>
    </div>

    <div class="download-section">
        <a href="https://play.google.com/store/apps/details?id=app.bpjs.mobile" target="_blank" class="btn-medical">
            <svg width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="white" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><path d="M21 15v4a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2v-4M7 10l5 5 5-5M12 15V3"/></svg>
            Instal Aplikasi Sekarang
        </a>
    </div>

    <div class="tutorial-grid">
        <div class="tutorial-content">
            <h4>🔐 Registrasi Identitas</h4>
            <ul>
                <li>Siapkan KTP dan nomor handphone aktif</li>
                <li>Pilih pendaftaran akun baru di aplikasi</li>
                <li>Lakukan verifikasi OTP secara real-time</li>
                <li>Buat kata sandi yang aman dan mudah diingat</li>
            </ul>
            <a href="https://youtube.com/shorts/ddc21BfzVwQ" target="_blank" class="watch-yt-link">Lihat di YouTube →</a>
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
            <h4>🗓️ Reservasi Antrian</h4>
            <ul>
                <li>Pilih menu pendaftaran pelayanan online</li>
                <li>Tentukan faskes rujukan dan poli spesialis</li>
                <li>Atur jadwal kunjungan sesuai ketersediaan</li>
                <li>Nomor antrian akan muncul secara otomatis</li>
            </ul>
            <a href="https://www.youtube.com/watch?v=sJ4f2V7uU-A" target="_blank" class="watch-yt-link">Lihat di YouTube →</a>
        </div>
    </div>

    <div class="back-section">
        <a href="{{ route('pendaftaran.online') }}" class="btn-back">Kembali ke Dashboard</a>
    </div>

</div>

@endsection