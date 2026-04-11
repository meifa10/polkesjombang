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

/* DOWNLOAD BUTTON */
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
    transition: all 0.3s ease;
    box-shadow: 0 8px 20px rgba(37, 99, 235, 0.3);
}

.btn-medical:hover {
    transform: translateY(-2px);
    box-shadow: 0 12px 25px rgba(37, 99, 235, 0.4);
}

/* SECTION CARD */
.tutorial-card {
    margin-top: 50px;
    padding: 35px;
    border-radius: 20px;
    background: #f9fbff;
    border: 1px solid #e2e8f0;
    box-shadow: 0 10px 25px rgba(0, 0, 0, 0.04);
}

.tutorial-card h4 {
    font-size: 20px;
    font-weight: 600;
    color: #1e40af;
    margin-bottom: 18px;
}

.tutorial-card ul {
    padding-left: 20px;
    line-height: 1.9;
    color: #334155;
    font-size: 15px;
}

/* VIDEO STYLE */
.video-container {
    margin-top: 25px;
    border-radius: 18px;
    overflow: hidden;
    box-shadow: 0 15px 35px rgba(0,0,0,0.08);
    background: #000;
}

.watch-on-yt {
    display: inline-block;
    margin-top: 15px;
    color: #2563eb;
    text-decoration: none;
    font-size: 14px;
    font-weight: 500;
}

.watch-on-yt:hover {
    text-decoration: underline;
}

/* BACK BUTTON */
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
    font-weight: 500;
    transition: 0.3s;
}

.btn-back:hover {
    background: #d97706;
}
</style>

<div class="jkn-wrapper">

    {{-- HEADER --}}
    <div class="jkn-header">
        <h2>Pendaftaran Pasien JKN (BPJS)</h2>
        <p>
            Pendaftaran layanan dilakukan melalui aplikasi resmi Mobile JKN.  
            Silakan ikuti panduan lengkap berikut untuk proses registrasi akun dan pendaftaran online.
        </p>
    </div>

    {{-- DOWNLOAD BUTTON --}}
    <div class="download-section">
        <a href="https://play.google.com/store/apps/details?id=app.bpjs.mobile"
           target="_blank"
           class="btn-medical">
           Download / Buka Aplikasi Mobile JKN
        </a>
    </div>

    {{-- ===================== TUTORIAL 1 ===================== --}}
    <div class="tutorial-card">
        <h4>📝 Tutorial Pembuatan Akun Mobile JKN</h4>
        <ul>
            <li>Unduh dan buka aplikasi Mobile JKN.</li>
            <li>Pilih menu <strong>Daftar</strong>.</li>
            <li>Masukkan Nomor Kartu BPJS / NIK dan Tanggal Lahir.</li>
            <li>Isi Email dan Nomor HP aktif.</li>
            <li>Masukkan kode verifikasi (OTP).</li>
            <li>Buat password dan login ke aplikasi.</li>
        </ul>

        <div class="video-container">
            {{-- Menggunakan format /embed/ agar bisa diputar langsung --}}
            <iframe width="100%" height="420"
                src="https://www.youtube.com/embed/ddc21BfzVwQ" 
                title="Tutorial Daftar Akun Mobile JKN"
                frameborder="0"
                allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture"
                allowfullscreen>
            </iframe>
        </div>
        <a href="https://youtube.com/shorts/ddc21BfzVwQ" target="_blank" class="watch-on-yt">
            ▶ Tonton langsung di YouTube
        </a>
    </div>

    {{-- ===================== TUTORIAL 2 ===================== --}}
    <div class="tutorial-card">
        <h4>📅 Tutorial Pendaftaran Online di Mobile JKN</h4>
        <ul>
            <li>Login ke aplikasi Mobile JKN.</li>
            <li>Pilih menu <strong>Pendaftaran Pelayanan</strong>.</li>
            <li>Pilih Faskes dan Poli tujuan.</li>
            <li>Tentukan tanggal pelayanan.</li>
            <li>Konfirmasi dan simpan nomor antrian digital.</li>
            <li>Tunjukkan nomor antrian saat datang ke fasilitas kesehatan.</li>
        </ul>

        <div class="video-container">
            {{-- Pastikan VIDEO_ID_2 diganti dengan ID video asli jika sudah ada --}}
            <iframe width="100%" height="420"
                src="https://www.youtube.com/embed/VIDEO_ID_2"
                title="Tutorial Pendaftaran Online Mobile JKN"
                frameborder="0"
                allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture"
                allowfullscreen>
            </iframe>
        </div>
        <a href="https://www.youtube.com/watch?v=VIDEO_ID_2" target="_blank" class="watch-on-yt">
            ▶ Tonton langsung di YouTube
        </a>
    </div>

    {{-- BACK BUTTON --}}
    <div class="back-section">
        <a href="{{ route('pendaftaran.online') }}" class="btn-back">
            Kembali ke Menu Pendaftaran
        </a>
    </div>

</div>

@endsection