@extends('layout.app')

@section('content')
<style>
.container {
    max-width: 900px;
    margin: 60px auto;
    padding: 0 20px;
}
.header {
    margin-bottom: 30px;
}
.header h1 {
    font-size: 28px;
    font-weight: 700;
}
.header p {
    color: #6b7280;
}
.card {
    background: #ffffff;
    border-radius: 18px;
    padding: 24px;
    box-shadow: 0 15px 30px rgba(0,0,0,.08);
    margin-bottom: 25px;
}
.card-header {
    display: flex;
    justify-content: space-between;
    margin-bottom: 14px;
}
.badge {
    background: #16a34a;
    color: white;
    font-size: 12px;
    padding: 4px 12px;
    border-radius: 999px;
}
.badge-red {
    background: #dc2626;
}
.grid {
    display: grid;
    grid-template-columns: 1fr 1fr;
    gap: 18px;
    margin-top: 15px;
}
.item label {
    font-size: 12px;
    color: #6b7280;
}
.item p {
    font-size: 14px;
    font-weight: 600;
    margin-top: 4px;
}
.btn {
    display: inline-block;
    margin-top: 20px;
    background: #2563eb;
    color: white;
    padding: 12px 26px;
    border-radius: 12px;
    text-decoration: none;
    font-weight: 600;
}
.pay-box {
    background: #f8fafc;
    border: 1px solid #e5e7eb;
    border-radius: 16px;
    padding: 20px;
    margin-top: 30px;
}
</style>

<div class="container">

    {{-- ================= HEADER ================= --}}
    <div class="header">
        <h1>Rekam Medis Saya</h1>
        <p>Riwayat pemeriksaan yang telah dilakukan oleh dokter</p>
        <p><strong>Poli:</strong> {{ $pendaftaran->poli }}</p>
        <p><strong>Kode Akses:</strong> {{ $pendaftaran->token_akses }}</p>
    </div>

    {{-- ================= REKAM MEDIS ================= --}}
    @if($rekamMedis->isEmpty())
        <div class="card">
            <p>Belum ada data rekam medis.</p>
        </div>
    @else
        @foreach($rekamMedis as $rm)
            <div class="card">
                <div class="card-header">
                    <strong>
                        {{ \Carbon\Carbon::parse($rm->created_at)->translatedFormat('d F Y') }}
                    </strong>
                    <span class="badge">Selesai</span>
                </div>

                <div class="grid">
                    <div class="item">
                        <label>Keluhan</label>
                        <p>{{ $rm->keluhan }}</p>
                    </div>

                    <div class="item">
                        <label>Diagnosis</label>
                        <p>{{ $rm->diagnosis }}</p>
                    </div>

                    <div class="item">
                        <label>Tindakan</label>
                        <p>{{ $rm->tindakan }}</p>
                    </div>

                    <div class="item">
                        <label>Resep</label>
                        <p>{{ $rm->resep }}</p>
                    </div>
                </div>
            </div>
        @endforeach
    @endif

    {{-- ================= PEMBAYARAN ================= --}}
    @if(isset($pembayaran))
        <div class="pay-box">
            <h3 style="font-size:18px;font-weight:700;margin-bottom:10px;">
                Informasi Pembayaran
            </h3>

            <p>
                <strong>Total Biaya:</strong>
                Rp {{ number_format($pembayaran->total_biaya) }}
            </p>

            <p>
                <strong>Status:</strong>
                @if($pembayaran->status === 'lunas')
                    <span class="badge">LUNAS</span>
                @else
                    <span class="badge badge-red">BELUM LUNAS</span>
                @endif
            </p>

            @if($pembayaran->status === 'belum_lunas')
                <p style="margin-top:10px;color:#dc2626;">
                    Silakan lakukan pembayaran di loket Polkes atau sesuai instruksi petugas.
                </p>
            @endif
        </div>
    @endif

    {{-- ================= PDF ================= --}}
    <a href="{{ route('pasien.rekammedis.pdf', $pendaftaran->token_akses) }}"
       class="btn">
        Download Rekam Medis (PDF)
    </a>

</div>
@endsection
