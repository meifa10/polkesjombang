@extends('layout.app')

@section('content')

<style>
:root {
    --green-main: #16a34a;
    --green-soft: rgba(22,163,74,.15);
}

/* ================= SECTION ================= */
.jadwal-section {
    padding: 90px 20px;
    background:
        radial-gradient(circle at top, rgba(22,163,74,.12), transparent 60%),
        #f8fafc;
}

/* ================= HEADER ================= */
.jadwal-header {
    text-align: center;
    margin-bottom: 60px;
}

.jadwal-header h1 {
    font-size: 36px;
    font-weight: 900;
}

.jadwal-header p {
    margin-top: 6px;
    color: #64748b;
}

/* ================= GRID ================= */
.jadwal-grid-top {
    max-width: 1100px;
    margin: auto;
    display: grid;
    grid-template-columns: repeat(3, 1fr);
    gap: 30px;
}

.jadwal-grid-bottom {
    max-width: 720px;
    margin: 40px auto 0;
    display: grid;
    grid-template-columns: repeat(2, 1fr);
    gap: 30px;
}

/* ================= CARD ================= */
.dokter-card {
    position: relative;
    background: rgba(255,255,255,.78);
    backdrop-filter: blur(16px);
    border-radius: 28px;
    padding: 26px 22px 32px;
    text-align: center;
    box-shadow:
        0 25px 60px rgba(0,0,0,.08);
    transition: .35s ease;
}

.dokter-card:hover {
    transform: translateY(-10px);
    box-shadow:
        0 35px 80px rgba(22,163,74,.25);
}

/* ================= FOTO (FIX KEPOTONG) ================= */
.dokter-card img {
    width: 160px;
    height: 200px;
    object-fit: cover;
    object-position: top; /* 🔥 INI KUNCINYA */
    border-radius: 22px;
    margin-bottom: 18px;
    box-shadow: 0 18px 35px rgba(0,0,0,.25);
}

/* ================= TEXT ================= */
.dokter-card h3 {
    font-size: 18px;
    font-weight: 800;
}

.dokter-card .poli {
    margin-top: 4px;
    font-size: 14px;
    font-weight: 700;
    color: var(--green-main);
}

.dokter-card .jadwal {
    margin-top: 12px;
    font-size: 13px;
    color: #475569;
    line-height: 1.6;
}

/* ================= BADGE ================= */
.badge-aktif {
    position: absolute;
    top: 18px;
    left: 18px;
    padding: 6px 16px;
    font-size: 12px;
    font-weight: 800;
    color: var(--green-main);
    background: var(--green-soft);
    border-radius: 999px;
}

/* ================= RESPONSIVE ================= */
@media (max-width: 900px) {
    .jadwal-grid-top {
        grid-template-columns: repeat(2, 1fr);
    }

    .jadwal-grid-bottom {
        grid-template-columns: 1fr;
        max-width: 360px;
    }
}

@media (max-width: 600px) {
    .jadwal-grid-top {
        grid-template-columns: 1fr;
    }
}
</style>

<section class="jadwal-section">

    <div class="jadwal-header">
        <h1>Jadwal Pelayanan</h1>
        <p>Polkes 05.09.15 Jombang</p>
    </div>

    {{-- ===== 3 ATAS ===== --}}
    <div class="jadwal-grid-top">
        @foreach(array_slice($dokters, 0, 3) as $d)
        <div class="dokter-card">
            @if($d['hari_ini'])
                <div class="badge-aktif">Hari Ini Praktik</div>
            @endif

            <img src="{{ asset('images/dokter/'.$d['foto']) }}">
            <h3>{{ $d['nama'] }}</h3>
            <div class="poli">{{ $d['poli'] }}</div>
            <div class="jadwal">
                {{ $d['hari'] }}<br>
                {{ $d['jam'] }}
            </div>
        </div>
        @endforeach
    </div>

    {{-- ===== 2 BAWAH ===== --}}
    <div class="jadwal-grid-bottom">
        @foreach(array_slice($dokters, 3, 2) as $d)
        <div class="dokter-card">
            @if($d['hari_ini'])
                <div class="badge-aktif">Hari Ini Praktik</div>
            @endif

            <img src="{{ asset('images/dokter/'.$d['foto']) }}">
            <h3>{{ $d['nama'] }}</h3>
            <div class="poli">{{ $d['poli'] }}</div>
            <div class="jadwal">
                {{ $d['hari'] }}<br>
                {{ $d['jam'] }}
            </div>
        </div>
        @endforeach
    </div>

</section>

@endsection
