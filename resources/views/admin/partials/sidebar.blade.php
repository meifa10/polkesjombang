<style>
.sidebar {
    width: 220px;
    background: #f4f6f8;
    padding: 20px;
    min-height: 100vh;
}

.sidebar a {
    display: block;
    padding: 10px 12px;
    margin-bottom: 8px;
    border-radius: 6px;
    text-decoration: none;
    color: #333;
    font-size: 14px;
}

.sidebar a:hover {
    background: #e0e7ff;
}

.logout {
    margin-top: 30px;
    background: #ffecec;
    color: #c0392b;
}
</style>

<div class="sidebar">
    <a href="{{ route('admin.dashboard') }}">Dashboard</a>
    <a href="#">Daftar Pasien</a>
    <a href="#">Pemeriksaan Pasien</a>
    <a href="#">Tulis Resep</a>
    <a href="#">Rekam Medis Digital</a>
    <a href="#">Pengaturan</a>

    <form method="POST" action="{{ route('logout') }}">
        @csrf
        <button class="logout">Logout</button>
    </form>
</div>
