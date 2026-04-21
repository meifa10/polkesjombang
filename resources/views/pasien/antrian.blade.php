@extends('layout.app')

@section('content')

<link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;700;800&display=swap" rel="stylesheet" crossorigin="anonymous">

<style>
    /* CSS RESET UNTUK RENDER CANVAS */
    #capture-zone * {
        -webkit-font-smoothing: antialiased;
        -moz-osx-font-smoothing: grayscale;
        text-shadow: none !important;
    }

    .antrian-container {
        min-height: 100vh;
        display: flex;
        align-items: center;
        justify-content: center;
        background: #111827; /* Fallback warna solid */
        background: radial-gradient(circle at top left, #064e3b 0%, #111827 100%);
        padding: 20px;
    }

    .integrated-ticket {
        background: #ffffff !important;
        width: 100%;
        max-width: 450px;
        border-radius: 30px;
        overflow: hidden;
        position: relative;
        /* Tambahkan border tipis agar batas tiket jelas di hosting */
        border: 1px solid #e5e7eb;
    }

    .ticket-header {
        background: #ffffff !important;
        padding: 40px 20px;
        text-align: center;
        border-bottom: 2px dashed #cbd5e1;
        position: relative;
    }

    /* Paksa Font Sistem jika Google Font Gagal di Hosting */
    .hospital-identity {
        font-family: 'Inter', -apple-system, BlinkMacSystemFont, "Segoe UI", Roboto, sans-serif;
        font-weight: 800;
        font-size: 18px;
        color: #10b981 !important;
        text-transform: uppercase;
        margin-bottom: 10px;
    }

    .ticket-display-number {
        font-family: 'Inter', Arial, sans-serif;
        font-size: 110px;
        font-weight: 900; /* Lebih tebal */
        color: #1e293b !important; /* Biru gelap pekat */
        line-height: 1;
        margin: 15px 0;
    }

    .ticket-body {
        background: #ffffff !important;
        padding: 30px;
    }

    .details-grid {
        display: grid;
        grid-template-columns: 1fr 1fr;
        gap: 20px;
    }

    .detail-item label {
        display: block;
        font-size: 11px;
        font-weight: 800;
        color: #64748b !important; /* Abu-abu solid */
        text-transform: uppercase;
        margin-bottom: 5px;
    }

    .detail-item span {
        font-size: 16px;
        font-weight: 700;
        color: #0f172a !important; /* Hitam pekat */
    }

    /* Lubang Tiket Adaptif */
    .ticket-header::before, .ticket-header::after {
        content: "";
        position: absolute;
        width: 30px;
        height: 30px;
        background: #0f1521; /* Samakan dengan BG container */
        border-radius: 50%;
        bottom: -15px;
    }
    .ticket-header::before { left: -15px; }
    .ticket-header::after { right: -15px; }

    .status-badge {
        display: inline-block;
        background: #d1fae5 !important;
        color: #065f46 !important;
        padding: 5px 12px;
        border-radius: 6px;
        font-weight: 800;
        font-size: 12px;
    }

    .ticket-footer {
        text-align: center;
        font-size: 12px;
        color: #94a3b8 !important;
        margin-top: 25px;
        line-height: 1.4;
    }

    .no-screenshot {
        padding: 20px 0;
    }

    .btn-download {
        background: #10b981;
        color: white;
        width: 100%;
        padding: 15px;
        border-radius: 12px;
        border: none;
        font-weight: 800;
        cursor: pointer;
        font-size: 16px;
    }
</style>

<div class="antrian-container">
    <div class="integrated-ticket" id="capture-zone">
        @isset($data)
        <div class="ticket-header">
            <div class="hospital-identity">POLKES JOMBANG</div>
            <div style="color: #64748b; font-weight: 700; font-size: 13px;">NOMOR ANTRIAN DIGITAL</div>
            <div class="ticket-display-number">
                {{ str_pad($data->nomor_antrian, 2, '0', STR_PAD_LEFT) }}
            </div>
            <div style="color: #334155; font-weight: 600;">
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
                    <label>Waktu</label>
                    <span>{{ \Carbon\Carbon::parse($data->created_at)->format('H:i') }} WIB</span>
                </div>
                <div class="detail-item">
                    <label>Status</label>
                    <div><span class="status-badge">{{ strtoupper($data->status) }}</span></div>
                </div>
            </div>

            <div class="no-screenshot">
                <button onclick="saveTicket()" class="btn-download">SIMPAN TIKET KE GALERI</button>
            </div>

            <div class="ticket-footer">
                Simpan tiket ini untuk ditunjukkan kepada petugas pendaftaran di RS.
            </div>
        </div>
        @endisset
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/html2canvas@1.4.1/dist/html2canvas.min.js"></script>
<script>
    function saveTicket() {
        const zone = document.getElementById('capture-zone');
        const btnArea = zone.querySelector('.no-screenshot');
        
        // Gunakan visibility agar layout tidak goyang saat render
        btnArea.style.visibility = 'hidden';

        html2canvas(zone, {
            scale: 3, // Skala 3 sudah sangat cukup untuk Hosting
            useCORS: true, // WAJIB untuk Hosting
            allowTaint: false,
            backgroundColor: "#ffffff",
            logging: false,
            width: zone.offsetWidth,
            height: zone.offsetHeight
        }).then(canvas => {
            btnArea.style.visibility = 'visible';
            
            const link = document.createElement('a');
            link.download = 'Antrian_{{ $data->nomor_antrian }}.png';
            link.href = canvas.toDataURL('image/png', 1.0);
            link.click();
        });
    }
</script>

@endsection