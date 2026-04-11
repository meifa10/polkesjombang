@extends('layout.app')

@section('content')

<style>
body {
    background: linear-gradient(to bottom right, #eef5ff, #f8fbff);
}

/* CONTAINER */
.jkn-wrapper {
    max-width: 1100px;
    margin: 70px auto;
    padding: 50px;
    background: #ffffff;
    border-radius: 24px;
    box-shadow: 0 20px 50px rgba(0, 0, 0, 0.07);
}

/* HEADER */
.jkn-header {
    text-align: center;
    margin-bottom: 50px;
}

.jkn-header h2 {
    font-size: 32px;
    font-weight: 700;
    color: #1e3a8a;
}

.jkn-header p {
    margin-top: 10px;
    font-size: 16px;
    color: #64748b;
}

/* BUTTON */
.download-section {
    text-align: center;
    margin-bottom: 50px;
}

.btn-medical {
    display: inline-block;
    background: linear-gradient(135deg, #2563eb, #1e40af);
    color: #fff;
    padding: 16px 40px;
    border-radius: 14px;
    font-size: 15px;
    font-weight: 600;
    text-decoration: none;
    transition: 0.3s;
}

.btn-medical:hover {
    transform: translateY(-2px);
}

/* CARD */
.tutorial-card {
    margin-top: 50px;
    padding: 35px;
    border-radius: 20px;
    background: #f9fbff;
    border: 1px solid #e2e8f0;
}

.tutorial-card h4 {
    color: #1e40af;
}

.tutorial-card ul {
    padding-left: 20px;
}

/* VIDEO */
.video-container {
    margin-top: 25px;
    border-radius: 18px;
    overflow: hidden;
    position: relative;
    background: #000;
}

/* OVERLAY */
.video-overlay {
    position: absolute;
    top: 0;
    left: 0;
    width: 100%;
    height: 100%;
    display: flex;
    align-items: center;
    justify-content: center;
}

.video-overlay div {
    background: rgba(0,0,0,0.6);
    padding: 15px 25px;
    border-radius: 50px;
    color: white;
    font-weight: 600;
}

/* LINK */
.watch-on-yt {
    display: inline-block;
    margin-top: 15px;
    color: #2563eb;
}

/* BACK */
.back-section {
    text-align: center;
    margin-top: 60px;
}

.btn-back {
    background: #f59e0b;
    color: white;
    padding: 14px 35px;
    border-radius: 12px;
    text-decoration: none;
}
</style>

<div class="jkn-wrapper">

    {{-- HEADER --}}
    <div class="jkn-header">
        <h2>Pendaftaran Pasien JKN (BPJS)</h2>
        <p>Ikuti panduan berikut untuk registrasi dan pendaftaran online.</p>
    </div>

    {{-- DOWNLOAD --}}
    <div class="download-section">
        <a href="https://play.google.com/store/apps/details?id=app.bpjs.mobile"
           target="_blank"
           class="btn-medical">
           Download / Buka Aplikasi Mobile JKN
        </a>
    </div>

    {{-- ===================== TUTORIAL 1 ===================== --}}
    <div class="tutorial-card">
        <h4>📝 Tutorial Pembuatan Akun</h4>

        <ul>
            <li>Download aplikasi</li>
            <li>Klik daftar</li>
            <li>Isi data</li>
            <li>Verifikasi OTP</li>
        </ul>

        <div class="video-container">

            <iframe width="100%" height="420"
                src="https://www.youtube.com/embed/ddc21BfzVwQ"
                frameborder="0"
                allowfullscreen>
            </iframe>

        </div>

        <a href="https://www.youtube.com/watch?v=ddc21BfzVwQ" target="_blank" class="watch-on-yt">
            ▶ Tonton di YouTube
        </a>
    </div>

    {{-- ===================== TUTORIAL 2 ===================== --}}
    <div class="tutorial-card">
        <h4>📅 Tutorial Pendaftaran Online</h4>

        <ul>
            <li>Login aplikasi</li>
            <li>Pilih pendaftaran</li>
            <li>Pilih poli</li>
            <li>Ambil antrian</li>
        </ul>

        {{-- THUMBNAIL (ANTI ERROR) --}}
        <div class="video-container">

            <a href="https://www.youtube.com/watch?v=sJ4f2V7uU-A" target="_blank">

                <img src="https://img.youtube.com/vi/sJ4f2V7uU-A/hqdefault.jpg"
                     style="width:100%; display:block;">

                <div class="video-overlay">
                    <div>▶ Klik untuk tonton</div>
                </div>

            </a>

        </div>

        <a href="https://www.youtube.com/watch?v=sJ4f2V7uU-A" target="_blank" class="watch-on-yt">
            ▶ Tonton di YouTube
        </a>
    </div>

    {{-- BACK --}}
    <div class="back-section">
        <a href="{{ route('pendaftaran.online') }}" class="btn-back">
            Kembali
        </a>
    </div>

</div>

@endsection