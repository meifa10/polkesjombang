@extends('layout.app')

@section('content')
<style>
    body { margin: 0; }

    .antrian-page {
        min-height: 100vh;
        display: flex;
        align-items: center;
        justify-content: center;
        background: linear-gradient(135deg, #34d399, #38bdf8);
        padding: 20px;
    }

    .antrian-card {
        background: white;
        width: 100%;
        max-width: 420px;
        border-radius: 24px;
        padding: 40px 28px;
        text-align: center;
        box-shadow: 0 25px 50px rgba(0,0,0,.18);
    }

    .antrian-title {
        font-size: 18px;
        font-weight: 600;
        color: #1f2937;
        margin-bottom: 4px;
    }

    .antrian-date {
        font-size: 14px;
        color: #6b7280;
        margin-bottom: 24px;
    }

    .antrian-number {
        font-size: 78px;
        font-weight: 800;
        color: #16a34a;
        margin-bottom: 20px;
        line-height: 1;
    }

    .divider {
        width: 100%;
        height: 1px;
        background: #e5e7eb;
        margin: 20px 0;
    }

    .antrian-info {
        background: #ecfdf5;
        border-radius: 16px;
        padding: 18px 20px;
        text-align: left;
        font-size: 14px;
        margin-bottom: 26px;
    }

    .antrian-info p {
        margin: 6px 0;
        color: #065f46;
        font-weight: 500;
    }

    .status-badge {
        display: inline-block;
        background: #22c55e;
        color: white;
        padding: 4px 12px;
        border-radius: 999px;
        font-size: 12px;
        font-weight: 600;
    }

    .token {
        margin-top: 10px;
        font-weight: 700;
        color: #0f766e;
        word-break: break-all;
    }

    .btn {
        display: block;
        width: 100%;
        padding: 12px;
        border-radius: 14px;
        font-size: 14px;
        font-weight: 600;
        margin-bottom: 12px;
        border: none;
        cursor: pointer;
        color: white;
        text-decoration: none;
    }

    .btn-home { background: #16a34a; }
    .btn-home:hover { background: #15803d; }

    .btn-download { background: #2563eb; }
    .btn-download:hover { background: #1d4ed8; }

    .antrian-note {
        margin-top: 18px;
        font-size: 13px;
        color: #6b7280;
        line-height: 1.5;
    }

    .no-capture { user-select: none; }
</style>

<div class="antrian-page">
    <div class="antrian-card" id="antrian-card">

        @if(isset($data))
            <div class="antrian-title">Nomor Antrian Anda</div>

            <div class="antrian-date">
                {{ \Carbon\Carbon::parse($data->created_at)->translatedFormat('l, d F Y') }}
            </div>

            <div class="antrian-number">
                {{ str_pad($data->nomor_antrian, 2, '0', STR_PAD_LEFT) }}
            </div>

            <div class="divider"></div>

            {{-- ✅ SATU BLOK INFO SAJA --}}
            <div class="antrian-info">
                <p>Nama Pasien : {{ $data->nama_pasien }}</p>
                <p>Poli        : {{ $data->poli }}</p>

                <p>Status :
                    <span class="status-badge">
                        {{ strtoupper($data->status) }}
                    </span>
                </p>

                @if(!empty($data->token_akses))
                    <p class="token">
                        Token Rekam Medis:<br>
                        {{ $data->token_akses }}
                    </p>
                @endif
            </div>

            <a href="{{ url('/') }}" class="btn btn-home no-capture">
                Kembali ke Beranda
            </a>

            <button class="btn btn-download no-capture" onclick="downloadAntrian()">
                Download Nomor Antrian
            </button>

            <div class="antrian-note">
                Simpan token rekam medis Anda.<br>
                Token digunakan untuk melihat hasil pemeriksaan.
            </div>
        @else
            <div class="antrian-title" style="color:red;">
                Data Antrian Tidak Ditemukan
            </div>

            <a href="{{ url('/') }}" class="btn btn-home">
                Kembali ke Beranda
            </a>
        @endif

    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/html2canvas@1.4.1/dist/html2canvas.min.js"></script>

<script>
function downloadAntrian() {
    const card = document.getElementById('antrian-card');
    const hidden = card.querySelectorAll('.no-capture');

    hidden.forEach(el => el.style.display = 'none');

    html2canvas(card, { scale: 3, backgroundColor: '#ffffff' })
        .then(canvas => {
            hidden.forEach(el => el.style.display = '');

            const link = document.createElement('a');
            link.download = 'nomor-antrian.png';
            link.href = canvas.toDataURL('image/png');
            link.click();
        });
}
</script>
@endsection
