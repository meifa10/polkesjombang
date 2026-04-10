<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Polkes 05.09.15 Jombang</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">

    <link rel="stylesheet" href="{{ asset('css/style.css') }}">

    <style>
        :root {
            --emerald-primary: #059669;
            --emerald-dark: #064e3b;
            --emerald-soft: #ecfdf5;
            --text-dark: #1e293b;
            --text-muted: #64748b;
        }

        body {
            font-family: 'Plus Jakarta Sans', sans-serif;
            margin: 0;
            padding: 0;
            background-color: #ffffff;
        }

        /* --- NAVBAR (NON-STICKY) --- */
        .navbar {
            background: #ffffff;
            position: relative; /* Navbar akan ikut ter-scroll ke atas */
            z-index: 1000;
            border-bottom: 1px solid #f1f5f9;
        }

        .nav-container {
            max-width: 1200px;
            margin: 0 auto;
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding: 20px 25px;
        }

        .logo {
            height: 60px;
            transition: 0.3s;
        }

        .nav-menu {
            display: flex;
            align-items: center;
            list-style: none;
            gap: 35px;
            margin: 0;
            padding: 0;
        }

        .nav-menu li a {
            text-decoration: none;
            color: var(--text-dark);
            font-weight: 700; /* Lebih tegas */
            font-size: 15px;
            transition: 0.3s;
            position: relative;
            padding: 10px 0;
            letter-spacing: 0.3px;
        }

        /* Garis bawah interaktif untuk menu aktif/hover */
        .nav-menu li a::after {
            content: '';
            position: absolute;
            width: 0;
            height: 3px;
            bottom: 0;
            left: 0;
            background: var(--emerald-primary);
            transition: 0.3s cubic-bezier(0.4, 0, 0.2, 1);
            border-radius: 50px;
        }

        .nav-menu li a:hover::after,
        .nav-menu li a.active::after {
            width: 100%;
        }

        .nav-menu li a:hover,
        .nav-menu li a.active {
            color: var(--emerald-primary);
        }

        /* --- TOMBOL PENDAFTARAN AESTHETIC --- */
        .btn-pendaftaran {
            background: linear-gradient(135deg, var(--emerald-primary) 0%, var(--emerald-dark) 100%);
            color: #ffffff !important;
            padding: 14px 30px !important;
            border-radius: 18px; 
            display: flex;
            align-items: center;
            gap: 12px;
            font-weight: 800 !important; /* Sangat Tegas */
            font-size: 14px !important;
            letter-spacing: 0.5px;
            box-shadow: 0 10px 20px rgba(5, 150, 105, 0.2);
            transition: all 0.4s cubic-bezier(0.175, 0.885, 0.32, 1.275) !important;
            border: none;
            text-transform: uppercase; /* Membuat kesan tombol aksi yang kuat */
        }

        .btn-pendaftaran:hover {
            transform: translateY(-5px);
            box-shadow: 0 15px 30px rgba(5, 150, 105, 0.4);
            background: linear-gradient(135deg, var(--emerald-dark) 0%, var(--emerald-primary) 100%);
        }

        .btn-pendaftaran i {
            font-size: 14px;
            transition: 0.3s;
        }

        .btn-pendaftaran:hover i {
            transform: translateX(5px);
        }

        .btn-pendaftaran::after {
            display: none !important; /* Hilangkan efek garis bawah pada tombol */
        }
    </style>
</head>
<body>

<header class="navbar">
    <div class="nav-container">
        <a href="{{ route('home') }}">
            <img src="{{ asset('images/logo.png') }}" class="logo" alt="Logo Polkes Jombang">
        </a>

        <ul class="nav-menu">
            <li>
                <a href="{{ route('home') }}" class="{{ Route::is('home') ? 'active' : '' }}">Home</a>
            </li>
            <li>
                <a href="{{ route('fasilitas') }}" class="{{ Route::is('fasilitas') ? 'active' : '' }}">Fasilitas</a>
            </li>
            <li>
                <a href="{{ route('profil.jadwal_dokter') }}" class="{{ Route::is('profil.jadwal_dokter') ? 'active' : '' }}">Jadwal Dokter</a>
            </li>
            <li>
                <a href="{{ route('contact') }}" class="{{ Route::is('contact') ? 'active' : '' }}">Contact</a>
            </li>

            {{-- Tombol Pendaftaran Premium --}}
            <li>
                <a href="{{ route('pendaftaran.online') }}" class="btn-pendaftaran">
                    <span>Pendaftaran Online</span>
                    <i class="fa-solid fa-arrow-right-long"></i>
                </a>
            </li>
        </ul>
    </div>
</header>

<main>
    @yield('content')
</main>

<footer class="footer-main">
    <div class="footer-container">

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

            <div class="footer-social">
                <a href="https://www.instagram.com/polkes050915_jombang/" target="_blank" class="social ig">
                    <i class="fab fa-instagram"></i>
                </a>
                <a href="https://www.facebook.com/share/17wnYzSBHT/" target="_blank" class="social fb">
                    <i class="fab fa-facebook-f"></i>
                </a>
                <a href="https://www.youtube.com/@poskesjombang4915" target="_blank" class="social yt">
                    <i class="fab fa-youtube"></i>
                </a>
                <a href="https://wa.me/6287777235386" target="_blank" class="social wa">
                    <i class="fab fa-whatsapp"></i>
                </a>
            </div>
        </div>

        <div class="footer-center">
            <h4>Tautan Cepat</h4>
            <ul class="footer-links">
                <li><a href="{{ route('home') }}">Home</a></li>
                <li><a href="{{ route('profile') }}">Tentang Kami</a></li>
                <li><a href="#">Layanan Dasar</a></li>
                <li><a href="#">Layanan Unggulan</a></li>
                <li><a href="#">Galeri Polkes</a></li>
                <li><a href="{{ route('contact') }}">Contact</a></li>
            </ul>
        </div>

        <div class="footer-right">
            <h4>Institusi Terkait</h4>
            <div class="institusi-logos">
                <img src="{{ asset('images/institusi/instansi1.png') }}" alt="Institusi 1">
                <img src="{{ asset('images/institusi/instansi2.png') }}" alt="Institusi 2">
                <img src="{{ asset('images/institusi/instansi3.png') }}" alt="Institusi 3">
                <img src="{{ asset('images/institusi/instansi4.png') }}" alt="Institusi 4">
            </div>
            <span class="footer-hashtag">#SehatBersamaPolkesJombang</span>
        </div>

    </div>

    <div class="footer-bottom">
        © {{ date('Y') }} Polkes 05.09.15 Jombang
    </div>
</footer>

<script>
    // Fungsi Modal Global
    function openModal(title, content) {
        document.getElementById('modalTitle').innerText = title;
        document.getElementById('modalContent').innerHTML = content;
        document.getElementById('modalOverlay').classList.add('show');
    }

    function closeModal() {
        document.getElementById('modalOverlay').classList.remove('show');
    }

    window.onclick = function(event) {
        const modal = document.getElementById('modalOverlay');
        if (event.target == modal) {
            closeModal();
        }
    }
</script>

<div id="modalOverlay" class="modal-overlay">
    <div class="modal-box">
        <span class="modal-close" onclick="closeModal()">×</span>
        <h3 id="modalTitle"></h3>
        <div id="modalContent"></div>
    </div>
</div>

</body>
</html>