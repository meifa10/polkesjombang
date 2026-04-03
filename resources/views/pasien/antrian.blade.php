@extends('layout.app')

@section('content')

<link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800&display=swap" rel="stylesheet">

<style>
    :root {
        --primary: #10b981;
        --primary-dark: #059669;
        --secondary: #3b82f6;
        --secondary-dark: #2563eb;
        --bg-main: #064e3b;
        --bg-accent: #111827;
        --card-white: #ffffff;
        --card-light: #f9fafb;
        --text-main: #111827;
        --text-muted: #6b7280;
        --text-white: #ffffff;
        --border-default: #e5e7eb;
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
        position: relative;
        overflow: hidden;
    }

    /* Dekorasi latar belakang yang menyatu dengan gradasi */
    .antrian-container::before {
        content: "";
        position: absolute;
        width: 600px;
        height: 600px;
        background: rgba(16, 185, 129, 0.15);
        filter: blur(120px);
        border-radius: 50%;
        top: -150px;
        right: -150px;
        z-index: 0;
    }

    /* integrated-ticket: Struktur tunggal tanpa jarak putih internal */
    .integrated-ticket {
        background: var(--card-white);
        width: 100%;
        max-width: 460px;
        border-radius: 36px;
        overflow: hidden;
        box-shadow: 0 40px 120px rgba(0,0,0,0.35);
        position: relative;
        z-index: 10;
        animation: cardSlideIn 0.7s cubic-bezier(0.19, 1, 0.22, 1);
    }

    @keyframes cardSlideIn {
        from { opacity: 0; transform: translateY(40px) scale(0.98); }
        to { opacity: 1; transform: translateY(0) scale(1); }
    }

    /* Header Tiket */
    .ticket-header {
        background: #f8fafc;
        padding: 35px 25px;
        text-align: center;
        border-bottom: 2px dashed #e2e8f0;
        position: relative;
    }

    /* Efek lubang tiket yang terintegrasi di samping */
    .ticket-header::before, .ticket-header::after {
        content: "";
        position: absolute;
        width: 32px;
        height: 32px;
        background: transparent;
        border-radius: 50%;
        bottom: -16px;
        z-index: 15;
        box-shadow: inset 0 0 10px rgba(0,0,0,0.1);
    }
    
    /* Warna latar belakang di dalam lubangtiket, samakan dengan gradasi container di posisi tersebut */
    .ticket-header::before { 
        left: -16px; 
        background-color: #064f3c; /* Samakan dengan bg-container */
    }
    .ticket-header::after { 
        right: -16px; 
        background-color: #0d121c; /* Samakan dengan bg-container */
    }

    .hospital-identity {
        font-weight: 800;
        font-size: 15px;
        letter-spacing: 1.5px;
        color: var(--primary);
        text-transform: uppercase;
        margin-bottom: 18px;
        display: flex;
        align-items: center;
        justify-content: center;
        gap: 10px;
    }

    .hospital-logo-icon {
        width: 20px;
        height: 20px;
        background-color: var(--primary);
        border-radius: 6px;
        display: inline-block;
    }

    .ticket-main-title {
        font-size: 14px;
        font-weight: 600;
        color: var(--text-muted);
        text-transform: uppercase;
        letter-spacing: 0.5px;
    }

    .ticket-display-number {
        font-size: 110px;
        font-weight: 800;
        color: var(--text-main);
        line-height: 1;
        margin: 10px 0;
        letter-spacing: -5px;
    }

    .ticket-display-date {
        font-size: 15px;
        font-weight: 500;
        color: var(--text-muted);
    }

    /* Body Tiket - Sekarang terhubung langsung */
    .ticket-body {
        padding: 35px 30px;
    }

    .details-grid {
        display: grid;
        grid-template-columns: 1fr 1fr;
        gap: 22px;
        margin-bottom: 30px;
    }

    .detail-item label {
        display: block;
        font-size: 11px;
        text-transform: uppercase;
        color: var(--text-muted);
        font-weight: 700;
        margin-bottom: 6px;
        letter-spacing: 0.5px;
    }

    .detail-item span {
        font-size: 16px;
        font-weight: 700;
        color: var(--text-main);
    }

    .status-badge {
        display: inline-flex;
        align-items: center;
        background: #ecfdf5;
        color: #059669;
        padding: 6px 16px;
        border-radius: 100px;
        font-size: 13px;
        font-weight: 700;
        border: 1px solid rgba(16,185,129,0.2);
    }

    .status-badge::before {
        content: "";
        width: 8px;
        height: 8px;
        background: #10b981;
        border-radius: 50%;
        margin-right: 8px;
        box-shadow: 0 0 10px rgba(16,185,129,0.5);
    }

    .rekam-medis-box {
        background: var(--card-light);
        border: 1px solid #e2e8f0;
        border-radius: 18px;
        padding: 20px;
        margin-top: 25px;
        text-align: center;
        box-shadow: 0 4px 6px rgba(0,0,0,0.02);
    }

    .box-label {
        font-size: 10px;
        font-weight: 700;
        color: #64748b;
        text-transform: uppercase;
        margin-bottom: 6px;
    }

    .box-value {
        font-family: 'Monaco', 'Courier New', monospace;
        font-size: 18px;
        color: #0f172a;
        font-weight: 700;
        letter-spacing: 1px;
    }

    /* Bagian Aksi - Bagian paling aesthetic yang menyatu */
    .ticket-actions {
        margin-top: 35px;
        padding-top: 25px;
        border-top: 1px dashed #e2e8f0;
    }

    .btn-integrated {
        display: flex;
        align-items: center;
        justify-content: center;
        gap: 12px;
        width: 100%;
        padding: 18px;
        border-radius: 20px;
        font-size: 15px;
        font-weight: 700;
        transition: all 0.3s cubic-bezier(0.165, 0.84, 0.44, 1);
        border: none;
        cursor: pointer;
        text-decoration: none;
        margin-bottom: 14px;
        box-sizing: border-box;
    }

    .btn-emerald {
        background: var(--primary);
        color: var(--text-white);
        box-shadow: 0 10px 25px rgba(16,185,129,0.3);
    }

    .btn-emerald:hover {
        background: var(--primary-dark);
        transform: translateY(-3px) scale(1.01);
        box-shadow: 0 15px 35px rgba(16,185,129,0.4);
    }

    .btn-outline-white {
        background: transparent;
        border: 2px solid #e2e8f0;
        color: var(--text-main);
    }

    .btn-outline-white:hover {
        background: #f8fafc;
        border-color: #cbd5e1;
        transform: translateY(-2px);
    }

    .ticket-footer {
        font-size: 12px;
        color: var(--text-muted);
        text-align: center;
        line-height: 1.6;
        margin-top: 25px;
    }

    .error-body {
        text-align: center;
        padding: 80px 30px;
    }

</style>

<div class="antrian-container">

    <div class="integrated-ticket" id="capture-zone">
        @isset($data)
        
        <div class="ticket-header">
            <span class="hospital-identity">
                <span class="hospital-logo-icon"></span>
                POLKES JOMBANG
            </span>
            <div class="ticket-main-title">Nomor Antrian Digital</div>
            <div class="ticket-display-number">
                {{ str_pad($data->nomor_antrian, 2, '0', STR_PAD_LEFT) }}
            </div>
            <div class="ticket-display-date">
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
                    <label>Status Layanan</label>
                    <div><span class="status-badge">{{ strtoupper($data->status) }}</span></div>
                </div>
            </div>

            @if(!empty($data->token_akses))
            <div class="rekam-medis-box">
                <div class="box-label">Token Rekam Medis</div>
                <div class="box-value">{{ $data->token_akses }}</div>
            </div>
            @endif

            <div class="ticket-actions no-screenshot">
                <button onclick="downloadEAntrianTicket()" class="btn-integrated btn-emerald">
                    <svg width="20" height="20" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4"></path></svg>
                    Simpan Nomor Antrian ke Galeri
                </button>

                <a href="{{ route('dashboard') }}" class="btn-integrated btn-outline-white">
                    <svg width="20" height="20" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m0 0l-7 7-7-7M19 10v10a1 1 0 00-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"></path></svg>
                    Kembali ke Dashboard
                </a>
            </div>

            <div class="ticket-footer">
                Harap datang 15 menit sebelum waktu pelayanan untuk verifikasi.<br>
                Nomor Antrian ini valid hanya untuk tanggal yang tertera di atas.
            </div>
        </div>

        @else
        <div class="error-body">
            <div style="font-size: 56px; margin-bottom: 25px;">🚫</div>
            <h3 style="color: #ef4444; font-weight: 800; font-size: 22px; margin-bottom: 10px;">NOMOR ANTRIAN TIDAK TERSEDIA</h3>
            <p style="color: var(--text-muted); margin-bottom: 35px; line-height: 1.6;">Maaf, data antrian Anda tidak tersedia atau telah kadaluarsa.</p>
            <a href="{{ route('dashboard') }}" class="btn-integrated btn-emerald">
                <svg width="20" height="20" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m0 0l-7 7-7-7M19 10v10a1 1 0 00-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"></path></svg>
                Kembali ke Dashboard
            </a>
        </div>
        @endisset
    </div>

</div>

<script src="https://cdn.jsdelivr.net/npm/html2canvas@1.4.1/dist/html2canvas.min.js"></script>

<script>
    function downloadEAntrianTicket() {
        const zone = document.getElementById('capture-zone');
        const actions = zone.querySelector('.no-screenshot');
        
        // Sembunyikan tombol saat proses screenshot
        actions.style.display = 'none';

        html2canvas(zone, {
            scale: 3, // Kualitas Tinggi
            backgroundColor: '#ffffff',
            borderRadius: 36,
        }).then(canvas => {
            // Tampilkan kembali tombol
            actions.style.display = 'block';

            const link = document.createElement('a');
            link.download = 'Nomor_Antrian_PolkesJombang_{{ $data->nomor_antrian ?? "00" }}.png';
            link.href = canvas.toDataURL('image/png');
            link.click();
        });
    }
</script>

@endsection