@extends('layout.app')

@section('content')

<link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;600;700;800&display=swap" rel="stylesheet">

<style>
    /* RESET UNTUK FULL SCREEN */
    :root {
        --primary: #0f172a;
        --accent: #2563eb;
        --text-muted: #64748b;
    }

    /* Memastikan background mentok ke atas dan bawah */
    body, html {
        margin: 0 !important;
        padding: 0 !important;
        width: 100%;
        min-height: 100vh;
    }

    .main-wrapper-full {
        background: radial-gradient(circle at 0% 0%, #f0f9ff 0%, #e0f2fe 50%, #fdfcfb 100%);
        min-height: 100vh; /* Full layar ke bawah */
        display: flex;
        flex-direction: column;
        padding: 0;
        margin: 0;
    }

    /* CONTAINER MELEBAR & MELEKAT KE ATAS */
    .jkn-wrapper {
        max-width: 1400px;
        width: 95%;
        margin: 0 auto; /* Menempel ke atas jika margin-top dihapus */
        padding: 80px 60px;
        background: rgba(255, 255, 255, 0.7);
        backdrop-filter: blur(30px);
        -webkit-backdrop-filter: blur(30px);
        border-radius: 0 0 60px 60px; /* Lengkung hanya di bawah supaya atas mentok */
        box-shadow: 0 40px 100px -20px rgba(0, 0, 0, 0.05);
        border: 1px solid rgba(255, 255, 255, 0.5);
        border-top: none;
    }

    /* HEADER SECTION */
    .jkn-header {
        text-align: center;
        margin-bottom: 60px;
    }

    .jkn-header h2 {
        font-family: 'Plus Jakarta Sans', sans-serif;
        font-size: clamp(32px, 5vw, 56px); /* Ukuran dinamis */
        font-weight: 800;
        letter-spacing: -2.5px;
        color: var(--primary);
        margin-bottom: 20px;
    }

    .jkn-header p {
        font-size: 19px;
        color: var(--text-muted);
        max-width: 750px;
        margin: 0 auto;
        line-height: 1.6;
    }

    /* DOWNLOAD BUTTON */
    .download-section {
        text-align: center;
        margin-bottom: 80px;
    }

    .btn-medical {
        display: inline-flex;
        align-items: center;
        gap: 14px;
        background: var(--primary);
        color: #fff;
        padding: 22px 55px;
        border-radius: 25px;
        font-size: 18px;
        font-weight: 700;
        text-decoration: none;
        transition: 0.4s cubic-bezier(0.2, 1, 0.3, 1);
        box-shadow: 0 25px 50px -10px rgba(15, 23, 42, 0.3);
    }

    .btn-medical:hover {
        transform: translateY(-5px);
        background: #000;
    }

    /* GRID TUTORIAL */
    .tutorial-grid {
        display: grid;
        grid-template-columns: 1fr 1.3fr;
        gap: 80px;
        align-items: center;
        margin-bottom: 100px;
        padding: 50px;
        background: rgba(255, 255, 255, 0.5);
        border-radius: 40px;
        border: 1px solid rgba(255, 255, 255, 0.8);
    }

    .tutorial-grid.reverse {
        grid-template-columns: 1.3fr 1fr;
    }

    .tutorial-content h4 {
        font-family: 'Plus Jakarta Sans', sans-serif;
        font-size: 32px;
        font-weight: 700;
        color: var(--primary);
        margin-bottom: 25px;
    }

    .tutorial-content ul {
        list-style: none;
        padding: 0;
        counter-reset: step;
    }

    .tutorial-content ul li {
        counter-increment: step;
        display: flex;
        align-items: center;
        padding: 20px 0;
        font-size: 18px;
        color: #334155;
        border-bottom: 1px solid rgba(0,0,0,0.05);
    }

    .tutorial-content ul li::before {
        content: counter(step);
        background: var(--primary);
        color: white;
        min-width: 35px;
        height: 35px;
        border-radius: 12px;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 14px;
        font-weight: 800;
        margin-right: 20px;
    }

    /* VIDEO BOX */
    .video-box {
        position: relative;
        border-radius: 40px;
        overflow: hidden;
        padding-top: 56.25%;
        background: #000;
        box-shadow: 0 40px 80px -20px rgba(0, 0, 0, 0.3);
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
        font-size: 16px;
    }

    /* FOOTER / BACK SECTION */
    .back-section {
        text-align: center;
        padding: 100px 0; /* Memberikan ruang ekstra di bawah */
    }

    .btn-back {
        color: var(--text-muted);
        text-decoration: none;
        font-weight: 600;
        padding: 20px 50px;
        border-radius: 20px;
        border: 2px solid #e2e8f0;
        transition: 0.3s;
        background: white;
    }

    /* RESPONSIVE */
    @media (max-width: 1100px) {
        .tutorial-grid, .tutorial-grid.reverse {
            grid-template-columns: 1fr;
            padding: 30px;
            gap: 40px;
        }
        .jkn-wrapper { padding: 60px 20px; }
    }
</style>

<div class="main-wrapper-full">
    <div class="jkn-wrapper">

        <div class="jkn-header">
            <h2>Layanan Digital JKN</h2>
            <p>Akses kesehatan jadi lebih mudah, cepat, dan modern. Pelajari cara penggunaannya di sini.</p>
        </div>

        <div class="download-section">
            <a href="https://play.google.com/store/apps/details?id=app.bpjs.mobile" target="_blank" class="btn-medical">
                <svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="white" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><path d="M21 15v4a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2v-4M7 10l5 5 5-5M12 15V3"/></svg>
                Dapatkan Aplikasi Resmi
            </a>
        </div>

        <div class="tutorial-grid">
            <div class="tutorial-content">
                <h4>🔐 Registrasi Akun</h4>
                <ul>
                    <li>Siapkan kartu BPJS dan NIK sesuai KTP</li>
                    <li>Input data diri pada menu pendaftaran</li>
                    <li>Lakukan verifikasi OTP lewat ponsel</li>
                    <li>Atur password unik untuk keamanan</li>
                </ul>
                <a href="https://youtube.com/shorts/ddc21BfzVwQ" target="_blank" class="watch-yt-link">Tonton Detail di YouTube →</a>
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
                <h4>🗓️ Pendaftaran Antrian</h4>
                <ul>
                    <li>Buka menu Pendaftaran Pelayanan Online</li>
                    <li>Pilih Faskes tujuan dan Poli spesialis</li>
                    <li>Pilih jadwal kedatangan yang tersedia</li>
                    <li>Simpan QR Code atau Nomor Antrian Anda</li>
                </ul>
                <a href="https://www.youtube.com/watch?v=sJ4f2V7uU-A" target="_blank" class="watch-yt-link">Tonton Detail di YouTube →</a>
            </div>
        </div>

        <div class="back-section">
            <a href="{{ route('pendaftaran.online') }}" class="btn-back">Kembali ke Dashboard</a>
        </div>

    </div>
</div>

@endsection