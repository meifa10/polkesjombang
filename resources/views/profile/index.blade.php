@extends('layout.app')

@section('content')

<style>
/* ================= GLOBAL & RESET ================= */
:root {
    --primary-green: #065f46;
    --accent-green: #16a34a;
}

/* ================= HERO REVISI (BIAR TIDAK MENUTUP LOGO) ================= */
.hero {
    position: relative;
    height: 100vh;
    min-height: 600px;
    display: flex;
    align-items: center;
    background: url('{{ asset('images/banner/green-bg.jpg') }}') center/cover no-repeat;
    overflow: hidden;
    padding-top: 80px;
}

.hero::before{
    content:'';
    position:absolute;
    inset:0;
    background:rgba(255,255,255,0.75); /* tingkat samar */
    backdrop-filter: blur(3px);
    z-index:1;
}

.hero-bg-wrapper {
    position: absolute;
    top: 0;
    right: 0;
    width: 65%;
    height: 100%;
    /* Clip-path gaya RS Pondok Indah */
    clip-path: polygon(15% 0, 100% 0, 100% 100%, 0% 100%);
    z-index: 1;
}

.hero-bg-wrapper img {
    width: 100%;
    height: 100%;
    object-fit: cover;
}

.hero-content {
    position: relative;
    z-index: 2;
    width: 100%;
    max-width: 1200px;
    margin: 0 auto;
    padding: 0 20px;
}

.hero-text-box {
    max-width: 550px;
    background: rgba(255, 255, 255, 0.95);
    backdrop-filter: blur(10px);
    padding: 50px;
    border-radius: 24px;
    box-shadow: 0 20px 40px rgba(0,0,0,0.05);
}

.hero-text-box h1 {
    font-size: 42px;
    font-weight: 800;
    color: var(--primary-green);
    line-height: 1.2;
    margin-bottom: 20px;
}

.hero-text-box p {
    font-size: 18px;
    color: #64748b;
    line-height: 1.6;
}

/* ================= JADWAL PREVIEW REVISI ================= */
.jadwal-preview {
    padding: 100px 20px;
    background: #ffffff;
    position: relative;
}

.jadwal-container {
    max-width: 1200px;
    margin: auto;
    display: grid;
    grid-template-columns: 1fr 1fr;
    gap: 60px;
    align-items: center;
}

.jadwal-main-img {
    width: 100%;
    border-radius: 30px;
    box-shadow: 0 25px 50px -12px rgba(0, 0, 0, 0.15);
}

.jadwal-content h2 {
    font-size: 38px;
    font-weight: 800;
    color: var(--primary-green);
    margin-bottom: 20px;
}

.btn-jadwal {
    display: inline-block;
    margin-top: 24px;
    padding: 16px 40px;
    border-radius: 50px;
    background: var(--primary-green);
    color: #fff;
    font-weight: 600;
    text-decoration: none;
    transition: .3s;
}

.btn-jadwal:hover {
    background: var(--accent-green);
    transform: translateY(-3px);
    box-shadow: 0 10px 20px rgba(22, 163, 74, 0.2);
}

/* ================= GALERI ================= */
.gallery {
    padding: 100px 20px;
    background: linear-gradient(
        135deg,
        #f1f5f9 0%,
        #e2f3ec 50%,
        #d9f2e6 100%
    );
}

.gallery h2 {
    text-align: center;
    font-size: 28px;
    font-weight: 700;
    color: #065f46;
    margin-bottom: 50px;
}

/* GRID RAPI & PROPORSIONAL */
.gallery-grid {
    max-width: 1200px;
    margin: auto;
    display: grid;
    grid-template-columns: repeat(3, 1fr);
    gap: 30px;
}

/* GAMBAR PROPORSIONAL (TIDAK GEDE BERLEBIHAN) */
.gallery-grid img {
    width: 100%;
    height: 260px;
    object-fit: cover;
    border-radius: 16px;
}

/* RESPONSIVE */
@media (max-width: 992px) {
    .gallery-grid {
        grid-template-columns: repeat(2, 1fr);
    }
}

@media (max-width: 768px) {
    .gallery-grid {
        grid-template-columns: 1fr;
    }
}

/* ================= HUBUNGI KAMI (CLEAN VERSION) ================= */
.contact-section {
    padding: 80px 20px;
    background: #f8fafc; /* clean putih soft */
}

/* Judul */
.contact-section .section-title {
    font-size: 28px;
    font-weight: 700;
    color: #065f46;
    margin-bottom: 50px;
}

/* Card lebih elegan */
.contact-card {
    background: #ffffff;
    border-radius: 16px;
    padding: 25px;
    box-shadow: 0 10px 30px rgba(0,0,0,0.06);
}
.contact-section {
    border-bottom: 1px solid #e5e7eb;
}

/* HILANGKAN JARAK ANTAR SECTION */
.contact-section {
    margin-bottom: 0 !important;
}

footer {
    margin-top: 0 !important;
}

/* ================= LAYANAN UNGGULAN RAPIII ================= */
.services {
    padding: 60px 20px;   /* sebelumnya 90px, ini bikin terlalu tinggi */
    margin: 0;            /* hilangkan jarak putih */
    text-align: center;
    background: linear-gradient(
        135deg,
        #f1f5f9 0%,
        #e2f3ec 50%,
        #d9f2e6 100%
    );
}

.services h2 {
    font-size: 26px;
    font-weight: 700;
    color: #065f46;
    margin-bottom: 35px;  /* lebih compact */
}

.service-list {
    max-width: 1100px;
    margin: auto;
    display: flex;
    flex-wrap: nowrap;      /* supaya tidak turun ke bawah */
    justify-content: center;
    gap: 18px;
    flex-wrap: wrap;        /* tetap responsif */
}

.service-list span {
    padding: 10px 20px;
    border-radius: 999px;
    border: 2px solid #16a34a;
    background: #ffffff;
    color: #065f46;
    font-size: 14px;
    white-space: nowrap;    /* supaya tidak pecah */
    transition: .3s ease;
}

.service-list span:hover {
    background: #16a34a;
    color: white;
}
/* HILANGKAN JARAK DARI SECTION SEBELUMNYA */
.basic-services {
    margin-bottom: 0 !important;
    padding-bottom: 0 !important;
}

/* Pastikan services nempel */
.services {
    margin-top: 0 !important;
}

/* tulisan selamat datang polkes */
.hero-overlay h1 {
    font-size: 38px;
    font-weight: 700;
    letter-spacing: 0.5px;
    text-shadow: 0 4px 20px rgba(0,0,0,0.4);
}

</style>


<!-- ================= HERO BARU ================= -->
<section class="hero">

<div class="hero-bg-wrapper">
<img src="{{ asset('images/banner/green-bg.jpg') }}">
</div>

<div class="hero-content">
<div class="hero-text-box">

<h1>Selamat Datang di Polkes 05.09.15 Jombang</h1>

<p>
Memberikan pelayanan kesehatan yang unggul,
profesional, dan humanis bagi prajurit serta
masyarakat umum di wilayah Jombang.
</p>

</div>
</div>

</section>

<!-- ================= TENTANG KAMI ================= -->
<section class="about">
    <div class="about-header">
        <h2>TENTANG KAMI</h2>
        <p class="about-desc">
            Polkes 05.09.15 Jombang adalah fasilitas pelayanan kesehatan
            yang memberikan layanan kesehatan bagi prajurit, keluarga,
            dan masyarakat umum.
        </p>
    </div>

    <div class="about-content">
        <div class="about-slider">
            <img src="{{ asset('images/about/gedung1.jpg') }}" class="active">
            <img src="{{ asset('images/about/gedung2.jpg') }}">
            <img src="{{ asset('images/about/gedung3.jpg') }}">
            <button class="slider-btn prev">&#10094;</button>
            <button class="slider-btn next">&#10095;</button>
        </div>

        <div class="about-text">
            <h3>Visi</h3>
            <p>Menuju Masyarakat Jombang Sehat.</p>

            <h3>Misi</h3>
            <ul>
                <li>Mendorong masyarakat untuk melaksanakan perilaku hidup sehat</li>
                <li>Memberikan pelayanan kesehatan yang cepat dan terjangkau</li>
                <li>Meningkatkan kemampuan dan keterampilan tenaga kesehatan</li>
            </ul>

            <h3>Tujuan</h3>
            <ul>
                <li>
                    Meningkatkan Derajat Kesehatan Bagi Anggota TNI, PNS,
                    Purnawirawan dan Keluarga, Serta Masyarakat Jombang Sekitarnya
                </li>
            </ul>

            <h3>Motto</h3>
            <div class="motto-box">“Melayani dengan Sepenuh Hati”</div>
        </div>
    </div>
</section>

<!-- ================= LAYANAN DASAR ================= -->
<section class="basic-services">
    <h2>LAYANAN DASAR</h2>

    <div class="basic-card"
    onclick="openModal(
    'Poli Umum',
    `
    <ul>
    <li>Pengobatan Umum</li>
    <li>Konsultasi Dokter Umum</li>
    <li>Asuhan Keperawatan</li>
    <li>Bedah Minor</li>
    <li>Rawat Luka Modern</li>
    <li>Jahit Luka</li>
    <li>PRB</li>
    <li>Prolanis</li>
    <li>Laboratorium Sederhana</li>
    <li>Nebulizer / Uap</li>
    <li>Rujukan</li>
    <li>Surat Keterangan (Sehat / Sakit)</li>
    </ul>
    `
    )">
        <div class="icon">🩺</div>
        <h4>Poli Umum</h4>
    </div>

    <div class="basic-card"
    onclick="openModal(
    'Poli Gigi',
    `
    <ul>
        <li>Pemeriksaan & Pengobatan Kesehatan Gigi</li>
        <li>Konsultasi Dokter Gigi</li>
        <li>Cabut Gigi</li>
        <li>Tambal Gigi</li>
        <li>Pembersihan Karang Gigi / Scalling (1x setahun)</li>
        <li>Rujukan</li>
    </ul>
    `
    )">
        <div class="icon">🦷</div>
        <h4>Poli Gigi</h4>
    </div>


    <div class="basic-card"
    onclick="openModal(
    'Poli KIA & KB',
    `
    <ul>
        <li>Konseling Pranikah</li>
        <li>Konseling Metode KB</li>
        <li>Pelayanan KB:
            <ul>
                <li>Kondom</li>
                <li>Pil</li>
                <li>Suntik (1 / 3 Bulan)</li>
                <li>Implant</li>
                <li>IUD</li>
            </ul>
        </li>
        <li>ANC (Antenatal Care)</li>
        <li>IVA / Pap Smear</li>
        <li>Tindik</li>
        <li>Rujukan Kasus KB</li>
    </ul>
    `
    )">
        <div class="icon">👶</div>
        <h4>Poli KIA & KB</h4>
    </div>
    </div>
</section>

<!-- ================= MODAL LAYANAN DASAR ================= -->
<div class="modal-overlay" id="modalOverlay">
    <div class="modal-box">
        <button class="modal-close" onclick="closeModal()">×</button>
        <h3 id="modalTitle"></h3>
        <p id="modalContent"></p>
    </div>
</div>

<!-- ================= LAYANAN UNGGULAN ================= -->
<section class="services">
    <h2>LAYANAN UNGGULAN</h2>
    <div class="service-list">
        <span>Medical Check Up / Rikkes</span>
        <span>Suntik Keloid</span>
        <span>Suntik Varises</span>
        <span>Khitan</span>
        <span>Fisioterapi</span>
        <span>One Day Care</span>
    </div>
</section>

<!-- ================= GALERI ================= -->
<section class="gallery">
    <h2>GALERI POLKES 05.09.15 JOMBANG</h2>
    <div class="gallery-grid">
        @foreach ($galleries as $g)
            <img src="{{ asset('images/' . $g->image) }}" alt="Galeri Polkes">
        @endforeach
    </div>
</section>

<!-- ================= JADWAL DOKTER BARU ================= -->

<section class="jadwal-preview">

<div class="jadwal-container">

<img src="{{ asset('images/dokter/dokter-group.png') }}" class="jadwal-main-img">

<div class="jadwal-content">

<h2>Temukan Jadwal Dokter Kami</h2>

<p>
Atur waktu kunjungan Anda dengan lebih mudah.
Kami menyediakan tenaga medis profesional
yang siap melayani konsultasi kesehatan Anda.
</p>

<a href="{{ route('profil.jadwal_dokter') }}" class="btn-jadwal">
Lihat Semua Jadwal
</a>

</div>

</div>

</section>

<!-- ================= HUBUNGI KAMI ================= -->
<section class="contact-section">
    <h2 class="section-title">HUBUNGI KAMI</h2>

    <div class="contact-wrapper">
        <div class="contact-cards">
            <div class="contact-card">
                <div class="icon">📍</div>
                <h4>ALAMAT</h4>
                <p>
                    Jl. KH. Wahid Hasyim No.28 B,<br>
                    Jombang, Jawa Timur 61419
                </p>
            </div>

            <div class="contact-card">
                <div class="icon">📞</div>
                <h4>HUBUNGI KAMI</h4>
                <p>0877-7723-5386</p>
            </div>

            <div class="contact-card">
                <div class="icon">✉️</div>
                <h4>EMAIL</h4>
                <p>jombangposkes@gmail.com</p>
            </div>

            <div class="contact-card">
                <div class="icon">⏰</div>
                <h4>JAM PELAYANAN</h4>
                <p>
                    UGD 24 Jam<br>
                    Piket Jaga Polkes
                </p>
            </div>
        </div>

        <div class="contact-map">
            <iframe
                src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d3955.318699785589!2d112.2369595!3d-7.5408235!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x2e784001f15b1bb1%3A0x5b665bb9b28fb68a!2sPOLKES%2005.09.15%20JOMBANG!5e0!3m2!1sid!2sid!4v1733720000000"
                allowfullscreen
                loading="lazy">
            </iframe>
        </div>
    </div>
</section>

@endsection
