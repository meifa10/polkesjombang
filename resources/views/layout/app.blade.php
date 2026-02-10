<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Polkes 05.09.15 Jombang</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <!-- FONT AWESOME -->
    <link rel="stylesheet"
          href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">

    <!-- CSS -->
    <link rel="stylesheet" href="{{ asset('css/style.css') }}">
</head>
<body>

<!-- ================= NAVBAR ================= -->
<header class="navbar">
    <div class="nav-container">
        <img src="{{ asset('images/logo.png') }}"
             class="logo"
             alt="Logo Polkes 05.09.15 Jombang">

        <ul class="nav-menu">
            <li><a href="{{ route('home') }}">Home</a></li>
            <li><a href="{{ route('fasilitas') }}">Fasilitas</a></li>

            {{-- ✅ MENU BARU --}}
            <li>
                <a href="{{ route('profil.jadwal_dokter') }}">
                    Jadwal Dokter
                </a>
            </li>

            <li><a href="{{ route('contact') }}">Contact</a></li>

            <li>
                <a href="{{ route('pendaftaran.online') }}" class="login">
                    Pendaftaran Online
                </a>
            </li>
        </ul>
    </div>
</header>

<!-- ================= KONTEN ================= -->
<main>
    @yield('content')
</main>

<!-- ================= FOOTER ================= -->
<footer class="footer-main">
    <div class="footer-container">

        <!-- KIRI -->
        <div class="footer-left">
            <h3>Polkes 05.09.15 Jombang</h3>
            <p>
                Jl. KH. Wahid Hasyim No.28 B<br>
                Jombang, Jawa Timur
            </p>
            <p>
                <strong>Telp / WA:</strong> 0877-7723-5386<br>
                <strong>Email:</strong> jombangposkes@gmail.com
            </p>

            <!-- SOSIAL MEDIA -->
            <div class="footer-social">
                <a href="https://www.instagram.com/polkes050915_jombang/"
                   target="_blank"
                   class="social ig">
                    <i class="fab fa-instagram"></i>
                </a>
                <a href="https://www.facebook.com/share/17wnYzSBHT/"
                   target="_blank"
                   class="social fb">
                    <i class="fab fa-facebook-f"></i>
                </a>
                <a href="https://www.youtube.com/@poskesjombang4915"
                   target="_blank"
                   class="social yt">
                    <i class="fab fa-youtube"></i>
                </a>
                <a href="https://wa.me/6287777235386"
                   target="_blank"
                   class="social wa">
                    <i class="fab fa-whatsapp"></i>
                </a>
            </div>
        </div>

        <!-- TENGAH -->
        <div class="footer-center">
            <h4>Useful Links</h4>
            <ul class="footer-links">
                <li><a href="{{ route('home') }}">Home</a></li>
                <li><a href="{{ route('profile') }}">Tentang Kami</a></li>
                <li><a href="#">Layanan Dasar</a></li>
                <li><a href="#">Layanan Unggulan</a></li>
                <li><a href="#">Galeri Polkes</a></li>
                <li><a href="{{ route('contact') }}">Contact</a></li>
            </ul>
        </div>

        <!-- KANAN -->
        <div class="footer-right">
            <h4>Institusi Terkait</h4>

            <div class="institusi-logos">
                <img src="{{ asset('images/institusi/instansi1.png') }}" alt="Institusi 1">
                <img src="{{ asset('images/institusi/instansi2.png') }}" alt="Institusi 2">
                <img src="{{ asset('images/institusi/instansi3.png') }}" alt="Institusi 3">
                <img src="{{ asset('images/institusi/instansi4.png') }}" alt="Institusi 4">
            </div>

            <span class="footer-hashtag">
                #SehatBersamaPolkesJombang
            </span>
        </div>

    </div>

    <!-- COPYRIGHT -->
    <div class="footer-bottom">
        © {{ date('Y') }} Polkes 05.09.15 Jombang
    </div>
</footer>

<!-- ================= JAVASCRIPT SLIDER ================= -->
<script>
document.addEventListener('DOMContentLoaded', function () {

    const slides = document.querySelectorAll('.about-slider img');
    const prevBtn = document.querySelector('.slider-btn.prev');
    const nextBtn = document.querySelector('.slider-btn.next');

    if (!slides.length || !prevBtn || !nextBtn) return;

    let current = 0;
    let interval;

    function showSlide(index) {
        slides.forEach(slide => slide.classList.remove('active'));
        slides[index].classList.add('active');
        current = index;
    }

    function nextSlide() {
        showSlide((current + 1) % slides.length);
    }

    function prevSlide() {
        showSlide((current - 1 + slides.length) % slides.length);
    }

    function startAutoSlide() {
        interval = setInterval(nextSlide, 5000);
    }

    function stopAutoSlide() {
        clearInterval(interval);
    }

    nextBtn.addEventListener('click', () => {
        stopAutoSlide();
        nextSlide();
        startAutoSlide();
    });

    prevBtn.addEventListener('click', () => {
        stopAutoSlide();
        prevSlide();
        startAutoSlide();
    });

    startAutoSlide();
});
</script>

<!-- ================= MODAL ================= -->
<script>
function openModal(title, content) {
    document.getElementById('modalTitle').innerText = title;
    document.getElementById('modalContent').innerHTML = content;
    document.getElementById('modalOverlay').classList.add('show');
}

function closeModal() {
    document.getElementById('modalOverlay').classList.remove('show');
}
</script>

<div id="modalOverlay" class="modal-overlay" onclick="closeModal()">
    <div class="modal-box" onclick="event.stopPropagation()">
        <span class="modal-close" onclick="closeModal()">×</span>
        <h3 id="modalTitle"></h3>
        <div id="modalContent"></div>
    </div>
</div>

</body>
</html>
