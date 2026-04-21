@extends('layout.app')

@section('content')

<link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;700;800&display=swap" rel="stylesheet" crossorigin="anonymous">

<style>
    /* CSS UNTUK MENGHILANGKAN SPACE PUTIH DI LAYOUT UTAMA */
    /* Kita paksa pembungkus utama Laravel untuk mengikuti tema gelap kita */
    html, body {
        background-color: #0d121c !important; 
        margin: 0;
        padding: 0;
    }

    .antrian-wrapper {
        width: 100%;
        /* Menggunakan vh (viewport height) agar selalu penuh setinggi layar */
        min-height: 100vh; 
        display: flex;
        align-items: center;
        justify-content: center;
        background: radial-gradient(circle at top left, #064e3b 0%, #0d121c 100%);
        padding: 40px 20px;
        position: relative;
        z-index: 999; /* Memastikan berada di atas elemen layout lain */
    }

    .integrated-ticket {
        background: #ffffff !important;
        width: 100%;
        max-width: 420px;
        border-radius: 30px;
        overflow: hidden;
        box-shadow: 0 30px 60px rgba(0,0,0,0.5);
    }

    .ticket-header {
        background: #ffffff !important;
        padding: 35px 20px;
        text-align: center;
        border-bottom: 2px dashed #cbd5e1;
        position: relative;
    }

    /* Lubang Tiket */
    .ticket-header::before, .ticket-header::after {
        content: "";
        position: absolute;
        width: 30px;
        height: 30px;
        background: #081d18; /* Sesuaikan warna gelap background luar */
        border-radius: 50%;
        bottom: -15px;
    }
    .ticket-header::before { left: -15px; }
    .ticket-header::after { right: -15px; }

    .hospital-identity {
        font-weight: 800;
        font-size: 18px;
        color: #10b981 !important;
        text-transform: uppercase;
    }

    .ticket-display-number {
        font-size: 100px;
        font-weight: 900;
        color: #1e293b !important;
        line-height: 1;
        margin: 10px 0;
    }

    .ticket-body {
        background: #ffffff !important;
        padding: 25px 30px;
    }

    .details-grid {
        display: grid;
        grid-template-columns: 1fr 1fr;
        gap: 15px;
        margin-bottom: 20px;
    }

    .detail-item label {
        display: block;
        font-size: 10px;
        font-weight: 800;
        color: #64748b !important;
        text-transform: uppercase;
    }

    .detail-item span {
        font-size: 15px;
        font-weight: 700;
        color: #0f172a !important;
    }

    .status-badge {
        display: inline-block;
        background: #d1fae5 !important;
        color: #065f46 !important;
        padding: 4px 10px;
        border-radius: 6px;
        font-weight: 800;
        font-size: 11px;
    }

    /* Tombol Navigasi */
    .no-screenshot {
        padding: 15px 0;
        border-top: 1px solid #f1f5f9;
        margin-top: 10px;
    }

    .btn-action {
        display: block;
        width: 100%;
        padding: 14px;
        border-radius: 12px;
        font-weight: 800;
        font-size: 14px;
        text-align: center;
        text-decoration: none;
        margin-bottom: 10px;
        border: none;
        cursor: pointer;
    }

    .btn-save { background: #10b981; color: white; }
    .btn-dashboard { background: #f1f5f9; color: #475569; border: 1px solid #e2e8f0; }

    .ticket-footer {
        text-align: center;
        font-size: 11px;
        color: #64748b !important;
        margin-top: 5px;
        line-height: 1.5;
    }
</style>

<div class="antrian-wrapper">
    <div class="integrated-ticket" id="capture-zone">
        @isset($data)
        <div class="ticket-header">
            <div class="hospital-identity">POLKES JOMBANG</div>
            <div style="color: #64748b; font-weight: 700; font-size: 12px; letter-spacing: 1px;">NOMOR ANTRIAN DIGITAL</div>
            <div class="ticket-display-number">
                {{ str_pad($data->nomor_antrian, 2, '0', STR_PAD_LEFT) }}
            </div>
            <div style="color: #334155; font-weight: 600; font-size: 14px;">
                {{ \Carbon\Carbon::parse($data->created_at)->translatedFormat('l, d F Y') }}
            </div>
        </div>

        <div class="ticket-body">
            <div class="details-grid">
                <div class="detail-item">
                    <label>Nama Pasien</label>
                    <span>{{ $data->nama_pasien }}</span>
                </div>
                <div class="detail-item">
                    <label>Layanan Poli</label>
                    <span>{{ $data->poli }}</span>
                </div>
                <div class="detail-item">
                    <label>Waktu Daftar</label>
                    <span>{{ \Carbon\Carbon::parse($data->created_at)->format('H:i') }} WIB</span>
                </div>
                <div class="detail-item">
                    <label>Status</label>
                    <div><span class="status-badge">{{ strtoupper($data->status) }}</span></div>
                </div>
            </div>

            <div class="no-screenshot">
                <button onclick="saveTicket()" class="btn-action btn-save">
                    SIMPAN TIKET KE GALERI
                </button>
                <a href="{{ route('dashboard') }}" class="btn-action btn-dashboard">
                    KEMBALI KE DASHBOARD
                </a>
            </div>

            <div class="ticket-footer">
                Harap datang 15 menit sebelum pelayanan.<br>
                Tunjukkan tiket digital ini kepada petugas.
            </div>
        </div>
        @endisset
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/html2canvas@1.4.1/dist/html2canvas.min.js"></script>
<script>
    function saveTicket() {
        const zone = document.getElementById('capture-zone');
        const noShow = zone.querySelector('.no-screenshot');
        
        noShow.style.display = 'none';

        html2canvas(zone, {
            scale: 3, 
            useCORS: true,
            backgroundColor: "#ffffff",
        }).then(canvas => {
            noShow.style.display = 'block';
            const link = document.createElement('a');
            link.download = 'Antrian_Polkes_{{ $data->nomor_antrian }}.png';
            link.href = canvas.toDataURL('image/png', 1.0);
            link.click();
        });
    }
</script>

@endsection