@extends('layout.app')

@section('content')

<link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800&display=swap" rel="stylesheet">

<style>
    :root {
        --primary: #10b981;
        --primary-dark: #059669;
        --bg-main: #064e3b;
        --bg-accent: #111827;
        --text-main: #111827;
        --text-muted: #4b5563; /* Dipergelap sedikit agar kontras */
    }

    body {
        margin: 0;
        font-family: 'Inter', sans-serif;
        background-color: var(--bg-accent);
    }

    .antrian-container {
        min-height: 100vh;
        display: flex;
        align-items: center;
        justify-content: center;
        background: radial-gradient(circle at top left, var(--bg-main) 0%, var(--bg-accent) 100%);
        padding: 30px;
    }

    /* Card Utama */
    .integrated-ticket {
        background: #ffffff;
        width: 100%;
        max-width: 460px;
        border-radius: 32px;
        overflow: hidden;
        box-shadow: 0 40px 100px rgba(0,0,0,0.4);
        position: relative;
    }

    /* Header Section */
    .ticket-header {
        background: #ffffff; /* Paksa putih solid */
        padding: 40px 25px 30px 25px;
        text-align: center;
        border-bottom: 2px dashed #d1d5db;
        position: relative;
    }

    .hospital-identity {
        font-weight: 800;
        font-size: 16px;
        color: #10b981; /* Warna solid */
        text-transform: uppercase;
        margin-bottom: 15px;
        display: flex;
        align-items: center;
        justify-content: center;
        gap: 8px;
    }

    .hospital-logo-icon {
        width: 18px;
        height: 18px;
        background-color: #10b981;
        border-radius: 4px;
    }

    .ticket-display-number {
        font-size: 120px;
        font-weight: 800;
        color: #111827; /* Hitam pekat */
        line-height: 1;
        margin: 10px 0;
        letter-spacing: -2px;
    }

    /* Body Section */
    .ticket-body {
        padding: 30px;
        background: #ffffff;
    }

    .details-grid {
        display: grid;
        grid-template-columns: 1fr 1fr;
        gap: 25px;
        margin-bottom: 25px;
    }

    .detail-item label {
        display: block;
        font-size: 12px;
        text-transform: uppercase;
        color: #6b7280; /* Abu-abu yang cukup gelap */
        font-weight: 700;
        margin-bottom: 4px;
    }

    .detail-item span {
        font-size: 18px;
        font-weight: 700;
        color: #111827; /* Hitam pekat */
    }

    .status-badge {
        display: inline-block;
        background: #d1fae5;
        color: #065f46;
        padding: 6px 14px;
        border-radius: 8px;
        font-size: 13px;
        font-weight: 800;
    }

    .ticket-footer {
        font-size: 13px;
        color: #6b7280;
        text-align: center;
        margin-top: 30px;
        line-height: 1.5;
        border-top: 1px solid #f3f4f6;
        padding-top: 20px;
    }

    /* Lubang Tiket */
    .ticket-header::before, .ticket-header::after {
        content: "";
        position: absolute;
        width: 30px;
        height: 30px;
        background-color: #0d121c; /* Warna background luar */
        border-radius: 50%;
        bottom: -15px;
    }
    .ticket-header::before { left: -15px; }
    .ticket-header::after { right: -15px; }

    /* Tombol - disembunyikan saat screenshot */
    .no-screenshot {
        margin-top: 25px;
    }

    .btn-integrated {
        width: 100%;
        padding: 16px;
        border-radius: 16px;
        font-weight: 700;
        border: none;
        cursor: pointer;
        margin-bottom: 10px;
        font-size: 15px;
        display: block;
        text-align: center;
        text-decoration: none;
    }

    .btn-emerald { background: #10b981; color: #ffffff; }
    .btn-outline { background: #ffffff; color: #111827; border: 2px solid #e5e7eb; }

</style>

<div class="antrian-container">
    <div class="integrated-ticket" id="capture-zone">
        @isset($data)
        <div class="ticket-header">
            <div class="hospital-identity">
                <div class="hospital-logo-icon"></div>
                POLKES JOMBANG
            </div>
            <div style="font-size: 13px; font-weight: 700; color: #6b7280; letter-spacing: 1px;">NOMOR ANTRIAN DIGITAL</div>
            <div class="ticket-display-number">
                {{ str_pad($data->nomor_antrian, 2, '0', STR_PAD_LEFT) }}
            </div>
            <div style="font-size: 16px; font-weight: 600; color: #374151;">
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

            @if(!empty($data->token_akses))
            <div style="background: #f9fafb; padding: 15px; border-radius: 12px; text-align: center; border: 1px solid #e5e7eb;">
                <div style="font-size: 10px; font-weight: 700; color: #9ca3af; margin-bottom: 4px;">TOKEN REKAM MEDIS</div>
                <div style="font-family: monospace; font-size: 20px; font-weight: 800; color: #111827;">{{ $data->token_akses }}</div>
            </div>
            @endif

            <div class="no-screenshot">
                <button onclick="downloadTicket()" class="btn-integrated btn-emerald">
                    Simpan Ke Galeri (HD)
                </button>
                <a href="{{ route('dashboard') }}" class="btn-integrated btn-outline">
                    Kembali
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
    function downloadTicket() {
        const zone = document.getElementById('capture-zone');
        const buttons = zone.querySelector('.no-screenshot');
        
        // Sembunyikan tombol
        buttons.style.visibility = 'hidden';

        html2canvas(zone, {
            scale: 3, // Skala 3 sudah cukup HD asal warna solid
            useCORS: true,
            backgroundColor: "#ffffff",
            letterRendering: true, // Membantu render teks lebih akurat
        }).then(canvas => {
            buttons.style.visibility = 'visible';
            
            const link = document.createElement('a');
            link.download = 'Antrian_Polkes_{{ $data->nomor_antrian ?? "01" }}.png';
            link.href = canvas.toDataURL('image/png', 1.0);
            link.click();
        });
    }
</script>

@endsection