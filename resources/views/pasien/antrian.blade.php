@extends('layout.app')

@section('content')

<link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;700;800&display=swap" rel="stylesheet" crossorigin="anonymous">

<style>
    /* CSS RESET & OPTIMASI */
    #capture-zone * {
        -webkit-font-smoothing: antialiased;
        -moz-osx-font-smoothing: grayscale;
        box-sizing: border-box;
    }

    /* Hilangkan margin/padding bawaan yang mungkin menyebabkan spasi putih */
    .antrian-wrapper {
        width: 100%;
        min-height: 100vh;
        display: flex;
        flex-direction: column;
        align-items: center;
        justify-content: center;
        background: #064e3b; /* Warna solid agar menyatu */
        background: radial-gradient(circle at top left, #064e3b 0%, #111827 100%);
        padding: 20px;
        margin: 0;
    }

    .integrated-ticket {
        background: #ffffff !important;
        width: 100%;
        max-width: 450px;
        border-radius: 30px;
        overflow: hidden;
        position: relative;
        box-shadow: 0 20px 50px rgba(0,0,0,0.3);
    }

    .ticket-header {
        background: #ffffff !important;
        padding: 35px 20px;
        text-align: center;
        border-bottom: 2px dashed #cbd5e1;
        position: relative;
    }

    .hospital-identity {
        font-family: 'Inter', sans-serif;
        font-weight: 800;
        font-size: 18px;
        color: #10b981 !important;
        text-transform: uppercase;
        margin-bottom: 5px;
    }

    .ticket-display-number {
        font-family: 'Inter', sans-serif;
        font-size: 110px;
        font-weight: 900;
        color: #1e293b !important;
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
        margin-bottom: 25px;
    }

    .detail-item label {
        display: block;
        font-size: 11px;
        font-weight: 800;
        color: #64748b !important;
        text-transform: uppercase;
        margin-bottom: 4px;
    }

    .detail-item span {
        font-size: 16px;
        font-weight: 700;
        color: #0f172a !important;
    }

    /* Lubang Tiket */
    .ticket-header::before, .ticket-header::after {
        content: "";
        position: absolute;
        width: 30px;
        height: 30px;
        background: #064e3b; /* Samakan dengan warna background luar terdekat */
        border-radius: 50%;
        bottom: -15px;
        z-index: 2;
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

    /* Tombol & Aksi */
    .no-screenshot {
        padding: 10px 0;
    }

    .btn-action {
        display: block;
        width: 100%;
        padding: 16px;
        border-radius: 14px;
        font-weight: 800;
        font-size: 15px;
        text-align: center;
        text-decoration: none;
        margin-bottom: 12px;
        border: none;
        cursor: pointer;
        transition: transform 0.2s;
    }

    .btn-action:active { transform: scale(0.98); }

    .btn-save { background: #10b981; color: white; }
    .btn-dashboard { background: #f1f5f9; color: #475569; border: 1px solid #e2e8f0; }

    .ticket-footer {
        text-align: center;
        font-size: 12px;
        color: #64748b !important;
        margin-top: 15px;
        line-height: 1.5;
        padding-top: 15px;
        border-top: 1px solid #f1f5f9;
    }
</style>

<div class="antrian-wrapper">
    <div class="integrated-ticket" id="capture-zone">
        @isset($data)
        <div class="ticket-header">
            <div class="hospital-identity">POLKES JOMBANG</div>
            <div style="color: #64748b; font-weight: 700; font-size: 13px;">NOMOR ANTRIAN DIGITAL</div>
            <div class="ticket-display-number">
                {{ str_pad($data->nomor_antrian, 2, '0', STR_PAD_LEFT) }}
            </div>
            <div style="color: #334155; font-weight: 600; font-size: 15px;">
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
        @else
        <div style="padding: 50px 20px; text-align: center;">
            <p>Data antrian tidak ditemukan.</p>
            <a href="{{ route('dashboard') }}" class="btn-action btn-dashboard">Kembali</a>
        </div>
        @endisset
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/html2canvas@1.4.1/dist/html2canvas.min.js"></script>
<script>
    function saveTicket() {
        const zone = document.getElementById('capture-zone');
        const noShow = zone.querySelector('.no-screenshot');
        
        // Sembunyikan elemen yang tidak ingin ada di gambar
        noShow.style.display = 'none';

        html2canvas(zone, {
            scale: 3, 
            useCORS: true,
            backgroundColor: "#ffffff",
            logging: false,
        }).then(canvas => {
            // Tampilkan kembali elemen setelah render selesai
            noShow.style.display = 'block';
            
            const link = document.createElement('a');
            link.download = 'Antrian_Polkes_{{ $data->nomor_antrian ?? "00" }}.png';
            link.href = canvas.toDataURL('image/png', 1.0);
            link.click();
        }).catch(err => {
            noShow.style.display = 'block';
            console.error("Gagal menyimpan gambar:", err);
        });
    }
</script>

@endsection