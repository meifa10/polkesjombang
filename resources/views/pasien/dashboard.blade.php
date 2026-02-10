@extends('layout.app')

@section('content')
<style>
    .dashboard-wrapper {
        background: linear-gradient(135deg, #4ae263ff, #5cc1ff);
        padding: 50px 0;
    }
    .dashboard-card {
        background: #ffffff;
        border-radius: 20px;
        padding: 35px;
        max-width: 900px;
        margin: auto;
        box-shadow: 0 10px 30px rgba(0,0,0,.1);
    }
    .menu-grid {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(130px, 1fr));
        gap: 30px;
        text-align: center;
    }
    .menu-item {
        text-decoration: none;
        color: #333;
    }
    .menu-icon {
        width: 65px;
        height: 65px;
        margin: auto;
        margin-bottom: 12px;
    }
    .menu-icon svg {
        width: 100%;
        height: 100%;
        fill: #4a90e2;
        transition: .25s;
    }
    .menu-item:hover svg {
        fill: #2c6ecf;
        transform: scale(1.08);
    }
    .menu-item span {
        display: block;
        font-size: 14px;
        font-weight: 500;
    }

    .kunjungan-wrapper {
        margin-top: 45px;
        background: linear-gradient(135deg, #2ecc71, #6fdc8c);
        border-radius: 18px;
        padding: 40px 20px;
    }
    .kunjungan-card {
        background: #ffffff;
        max-width: 460px;
        margin: auto;
        border-radius: 18px;
        padding: 35px 25px;
        text-align: center;
        box-shadow: 0 10px 30px rgba(0,0,0,0.12);
    }
    .kunjungan-title {
        font-size: 18px;
        font-weight: 600;
        margin-bottom: 20px;
    }
    .badge {
        display: inline-block;
        padding: 6px 14px;
        border-radius: 20px;
        font-size: 13px;
        font-weight: 600;
    }
    .menunggu { background: #fde68a; color: #92400e; }
    .diproses { background: #bfdbfe; color: #1e40af; }
    .selesai  { background: #bbf7d0; color: #166534; }
</style>

<div class="dashboard-wrapper">
    <div class="dashboard-card">

        <!-- MENU -->
        <div class="menu-grid">
            <a href="{{ route('pendaftaran.poliklinik') }}" class="menu-item">
                <div class="menu-icon">
                    <svg viewBox="0 0 24 24"><path d="M19 3H5v18h14V3zm-7 14h-2v-3H7v-2h3V9h2v3h3v2h-3v3z"/></svg>
                </div>
                <span>Pendaftaran Poliklinik</span>
            </a>

            <a href="#" class="menu-item">
                <div class="menu-icon">
                    <svg viewBox="0 0 24 24"><path d="M12 2a10 10 0 1 0 10 10A10 10 0 0 0 12 2z"/></svg>
                </div>
                <span>Informasi Antrian</span>
            </a>

            <a href="#" class="menu-item">
                <div class="menu-icon">
                    <svg viewBox="0 0 24 24"><path d="M19 3h-1V1h-2v2H8V1H6v2H5v18h14V3z"/></svg>
                </div>
                <span>Jadwal Dokter</span>
            </a>

            <a href="#" class="menu-item">
                <div class="menu-icon">
                    <svg viewBox="0 0 24 24"><path d="M6 2h12v20H6z"/></svg>
                </div>
                <span>Rekam Medis</span>
            </a>
        </div>

        <!-- KUNJUNGAN TERAKHIR -->
        <div class="kunjungan-wrapper">
            <div class="kunjungan-card">

                <div class="kunjungan-title">Kunjungan Terakhir</div>

                @if($kunjungan)
                    <p class="text-sm mb-2">Nama Pasien</p>
                    <p class="font-semibold mb-3">{{ $kunjungan->nama_pasien }}</p>

                    <p class="text-sm">Poli</p>
                    <p class="font-semibold mb-3">{{ $kunjungan->poli }}</p>

                    <p class="text-sm">Nomor Antrian</p>
                    <p class="text-3xl font-bold text-emerald-600 mb-4">
                        {{ $kunjungan->nomor_antrian }}
                    </p>

                    <span class="badge {{ $kunjungan->status }}">
                        {{ strtoupper($kunjungan->status) }}
                    </span>
                @else
                    <p class="text-gray-600">
                        Anda belum memiliki pendaftaran pemeriksaan.
                    </p>
                @endif

            </div>
        </div>

    </div>
</div>
@endsection
