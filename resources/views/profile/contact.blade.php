@extends('layout.app')

@section('content')
<style>
/* ================= RESET ================= */
* {
    margin: 0;
    padding: 0;
    box-sizing: border-box;
}

body {
    font-family: 'Inter', system-ui, -apple-system, sans-serif;
    background: #f8fafc;
    color: #1e293b;
}

/* ================= SECTION ================= */
.contact-section {
    padding: 120px 20px 80px;
}

.container {
    max-width: 1200px;
    margin: auto;
}

/* ================= HEADER ================= */
.contact-header {
    text-align: center;
    margin-bottom: 80px;
}

.contact-header h1 {
    font-size: 42px;
    font-weight: 800;
    margin-bottom: 16px;
}

.contact-header p {
    font-size: 18px;
    color: #64748b;
    max-width: 620px;
    margin: auto;
}

/* ================= GRID ================= */
.contact-grid {
    display: grid;
    grid-template-columns: 1fr 1fr;
    gap: 50px;
}

/* ================= INFO CARDS ================= */
.info-grid {
    display: grid;
    grid-template-columns: repeat(2, 1fr);
    gap: 24px;
}

.contact-card {
    background: #ffffff;
    padding: 32px;
    border-radius: 28px;
    box-shadow: 0 10px 25px rgba(0,0,0,.06);
    transition: .3s ease;
}

.contact-card:hover {
    transform: translateY(-6px);
    box-shadow: 0 20px 45px rgba(0,0,0,.12);
}

.contact-card .icon {
    width: 56px;
    height: 56px;
    background: #d1fae5;
    color: #047857;
    border-radius: 18px;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 24px;
    margin-bottom: 20px;
}

.contact-card h4 {
    font-size: 14px;
    letter-spacing: 1px;
    margin-bottom: 12px;
    color: #334155;
}

.contact-card p,
.contact-card a {
    font-size: 15px;
    color: #475569;
    line-height: 1.6;
    text-decoration: none;
}

.contact-card a {
    color: #059669;
    font-weight: 600;
}

.contact-card a:hover {
    text-decoration: underline;
}

/* ================= MAP ================= */
.map-box {
    position: relative;
    border-radius: 28px;
    overflow: hidden;
    box-shadow: 0 20px 45px rgba(0,0,0,.18);
}

.map-box iframe {
    width: 100%;
    height: 520px;
    border: none;
}

.map-overlay {
    position: absolute;
    bottom: 20px;
    left: 20px;
    background: rgba(255,255,255,.95);
    backdrop-filter: blur(8px);
    padding: 16px 20px;
    border-radius: 18px;
    box-shadow: 0 10px 25px rgba(0,0,0,.15);
}

.map-overlay h4 {
    font-size: 16px;
    font-weight: 700;
}

.map-overlay span {
    font-size: 13px;
    color: #64748b;
}

/* ================= SOCIAL MEDIA ================= */
.social-wrapper {
    margin-top: 80px;
    display: flex;
    justify-content: center;
}

.social-card {
    background: #ffffff;
    padding: 26px 36px;
    border-radius: 32px;
    box-shadow: 0 20px 45px rgba(0,0,0,.12);
    display: flex;
    gap: 18px;
}

.social-card a {
    width: 52px;
    height: 52px;
    border-radius: 50%;
    color: #fff;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 22px;
    transition: .3s ease;
}

.social-card a:hover {
    transform: translateY(-4px) scale(1.05);
}

.social-ig { background: #e1306c; }
.social-fb { background: #1877f2; }
.social-yt { background: #ff0000; }
.social-wa { background: #25d366; }

/* ================= RESPONSIVE ================= */
@media(max-width: 900px) {
    .contact-grid {
        grid-template-columns: 1fr;
    }

    .info-grid {
        grid-template-columns: 1fr;
    }

    .contact-header h1 {
        font-size: 34px;
    }
}
</style>

<section class="contact-section">
    <div class="container">

        {{-- HEADER --}}
        <div class="contact-header">
            <h1>Kontak & Informasi Layanan</h1>
            <p>
                Kami siap melayani kebutuhan kesehatan Anda dengan pelayanan
                profesional di Polkes 05.09.15 Jombang.
            </p>
        </div>

        {{-- MAIN GRID --}}
        <div class="contact-grid">

            {{-- LEFT --}}
            <div class="info-grid">

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
                    <p>
                        <a href="https://wa.me/6287777235386">
                            0877-7723-5386 (WhatsApp)
                        </a>
                    </p>
                </div>

                <div class="contact-card">
                    <div class="icon">✉️</div>
                    <h4>EMAIL</h4>
                    <p>
                        <a href="mailto:jombangposkes@gmail.com">
                            jombangposkes@gmail.com
                        </a>
                    </p>
                </div>

                <div class="contact-card">
                    <div class="icon">⏰</div>
                    <h4>JAM PELAYANAN</h4>
                    <p>
                        IGD <strong>24 Jam</strong><br>
                        Poli Senin – Jumat
                    </p>
                </div>

            </div>

            {{-- RIGHT : MAP --}}
            <div class="map-box">
                <iframe
                    src="https://www.google.com/maps?q=POLKES%2005.09.15%20JOMBANG&output=embed"
                    loading="lazy"
                    referrerpolicy="no-referrer-when-downgrade">
                </iframe>

                <div class="map-overlay">
                    <h4>Polkes 05.09.15 Jombang</h4>
                    <span>Jl. KH. Wahid Hasyim No.28 B</span>
                </div>
            </div>

        </div>

        {{-- SOCIAL MEDIA --}}
        <div class="social-wrapper">
            <div class="social-card">
                <a href="https://www.instagram.com/polkes050915_jombang/"
                   target="_blank" class="social-ig">
                    <i class="fab fa-instagram"></i>
                </a>
                <a href="https://www.facebook.com/share/17wnYzSBHT/"
                   target="_blank" class="social-fb">
                    <i class="fab fa-facebook-f"></i>
                </a>
                <a href="https://www.youtube.com/@poskesjombang4915"
                   target="_blank" class="social-yt">
                    <i class="fab fa-youtube"></i>
                </a>
                <a href="https://wa.me/6287777235386"
                   target="_blank" class="social-wa">
                    <i class="fab fa-whatsapp"></i>
                </a>
            </div>
        </div>

    </div>
</section>
@endsection
