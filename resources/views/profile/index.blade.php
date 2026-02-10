@extends('layout.app')

@section('content')

<style>
/* ================= PREVIEW JADWAL DOKTER ================= */
.jadwal-preview {
    position: relative;
    background: url('{{ asset('images/banner/green-bg.jpg') }}') center/cover no-repeat;
    padding: 90px 20px;
    margin: 0;
}

.jadwal-preview::before {
    content: '';
    position: absolute;
    inset: 0;
    background: rgba(20, 120, 80, 0.78);
}

/* FADE HALUS KE BAWAH */
.jadwal-preview::after {
    content: '';
    position: absolute;
    bottom: 0;
    left: 0;
    width: 100%;
    height: 90px;
    background: linear-gradient(
        to bottom,
        rgba(20,120,80,0),
        #f8fafc
    );
}

.jadwal-preview .container {
    position: relative;
    max-width: 1200px;
    margin: auto;
    display: flex;
    align-items: center;
    justify-content: space-between;
    gap: 40px;
    z-index: 2;
    color: #ecfdf5;
}

.jadwal-text h2 {
    font-size: 34px;
    font-weight: 700;
}

.jadwal-text h3 {
    margin-top: 6px;
    font-size: 18px;
    font-weight: 500;
    opacity: .95;
}

.jadwal-text p {
    margin-top: 14px;
    max-width: 420px;
    font-size: 14px;
    line-height: 1.7;
    opacity: .9;
}

.btn-jadwal {
    display: inline-block;
    margin-top: 24px;
    padding: 13px 34px;
    border-radius: 999px;
    background: rgba(34, 197, 94, 0.9);
    color: #fff;
    font-weight: 600;
    text-decoration: none;
    transition: .3s;
    box-shadow: 0 10px 25px rgba(0,0,0,.25);
}

.btn-jadwal:hover {
    background: rgba(22, 163, 74, 1);
    transform: translateY(-3px);
}

.jadwal-image img {
    max-width: 400px;
    filter: drop-shadow(0 15px 35px rgba(0,0,0,.35));
}

/* RESPONSIVE */
@media (max-width: 768px) {
    .jadwal-preview .container {
        flex-direction: column;
        text-align: center;
    }

    .jadwal-image img {
        max-width: 280px;
    }
}
</style>

<!-- ================= HERO ================= -->
<section class="hero">
    <div class="hero-blur"></div>
    <div class="hero-overlay">
        <h1>Selamat Datang di Polkes 05.09.15 Jombang</h1>
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

<!-- ================= STATISTIK ================= -->
<section class="stats">
    <div class="stat-card"><h3>3</h3><p>Kamar</p></div>
    <div class="stat-card"><h3>3</h3><p>Poli</p></div>
    <div class="stat-card"><h3>3</h3><p>Dokter</p></div>
    <div class="stat-card"><h3>17</h3><p>Karyawan</p></div>
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

<!-- ================= JADWAL DOKTER (DITAMBAHKAN DI SINI) ================= -->
<section class="jadwal-preview">
    <div class="container">
        <div class="jadwal-text">
            <h2>Jadwal Dokter</h2>
            <h3>Polkes 05.09.15 Jombang</h3>
            <p>
                Lihat jadwal praktik dokter secara lengkap dan terbaru
                untuk pelayanan kesehatan Anda.
            </p>

            <a href="{{ route('profil.jadwal_dokter') }}" class="btn-jadwal">
                Lihat Jadwal
            </a>
        </div>

        <div class="jadwal-image">
            <img src="{{ asset('images/dokter/dokter-group.png') }}" alt="Dokter Polkes">
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
