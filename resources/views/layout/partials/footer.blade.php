<style>
.footer-main {
    background: #3f8f4e;
    color: #fff;
    padding: 40px 0 0;
    margin-top: 60px;
}

.footer-container {
    max-width: 1100px;
    margin: auto;
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
    gap: 30px;
    padding: 0 20px;
}

.footer-left h3 {
    margin-bottom: 10px;
}

.footer-left p {
    font-size: 14px;
    line-height: 1.6;
}

.footer-social {
    margin-top: 15px;
}

.footer-social a {
    display: inline-flex;
    width: 36px;
    height: 36px;
    background: #fff;
    color: #3f8f4e;
    align-items: center;
    justify-content: center;
    border-radius: 50%;
    margin-right: 8px;
    text-decoration: none;
    font-size: 14px;
}

.footer-center ul {
    list-style: none;
    padding: 0;
}

.footer-center ul li {
    margin-bottom: 8px;
}

.footer-center ul li a {
    color: #fff;
    text-decoration: none;
    font-size: 14px;
}

.footer-right img {
    height: 40px;
    background: #fff;
    padding: 5px;
    border-radius: 6px;
    margin-right: 8px;
}

.footer-hashtag {
    display: inline-block;
    margin-top: 12px;
    background: #2d6b3a;
    padding: 6px 12px;
    border-radius: 20px;
    font-size: 12px;
}

.footer-bottom {
    background: #2d6b3a;
    text-align: center;
    padding: 12px;
    font-size: 13px;
    margin-top: 30px;
}
</style>

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


        <!-- TENGAH -->
        <div class="footer-center">
            <h4>Useful Links</h4>
            <ul>
                <li><a href="/">Home</a></li>
                <li><a href="/profile">Tentang Kami</a></li>
                <li><a href="#">Layanan Dasar</a></li>
                <li><a href="#">Layanan Unggulan</a></li>
                <li><a href="#">Galeri Polkes</a></li>
                <li><a href="#">Dokter Spesialis</a></li>
                <li><a href="/contact">Contact</a></li>
            </ul>
        </div>

        <!-- KANAN -->
        <div class="footer-right">
            <h4>Institusi Terkait</h4>
            <div>
                <img src="{{ asset('images/institusi/instansi1.png') }}">
                <img src="{{ asset('images/institusi/instansi2.png') }}">
                <img src="{{ asset('images/institusi/instansi3.png') }}">
                <img src="{{ asset('images/institusi/instansi4.png') }}">
            </div>
            <span class="footer-hashtag">#SehatBersamaPolkesJombang</span>
        </div>

    </div>

    <div class="footer-bottom">
        © {{ date('Y') }} Polkes 05.09.15 Jombang
    </div>
</footer>
