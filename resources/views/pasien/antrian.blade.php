@extends('layout.app')

@section('content')

<link href="https://fonts.googleapis.com/css2?family=Inter:wght=400;500;700;800;900&display=swap" rel="stylesheet">

<style>
    html, body {
        background-color: #0d121c !important; 
        margin: 0;
        padding: 0;
        font-family: 'Inter', sans-serif;
    }

    .antrian-wrapper {
        width: 100%;
        min-height: 100vh; 
        display: flex;
        align-items: center;
        justify-content: center;
        background: radial-gradient(circle at top left, #064e3b 0%, #0d121c 100%);
        padding: 40px 20px;
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
        padding: 35px 20px 20px 20px;
        text-align: center;
        position: relative;
    }

    /* Efek garis putus robekan tiket rumah sakit */
    .ticket-tear-line {
        border-top: 2px dashed #cbd5e1;
        position: relative;
        margin: 12px 0;
    }

    .ticket-tear-line::before, .ticket-tear-line::after {
        content: "";
        position: absolute;
        width: 26px;
        height: 26px;
        background: #081d18; 
        border-radius: 50%;
        top: -13px;
    }
    .ticket-tear-line::before { left: -33px; }
    .ticket-tear-line::after { right: -33px; }

    .hospital-identity {
        font-weight: 800;
        font-size: 18px;
        color: #10b981 !important;
        text-transform: uppercase;
        letter-spacing: 0.5px;
    }

    .ticket-display-number {
        font-size: 85px;
        font-weight: 900;
        color: #1e293b !important;
        line-height: 1;
        margin: 10px 0;
        letter-spacing: -2px;
    }

    .ticket-body {
        background: #ffffff !important;
        padding: 20px 30px 30px 30px;
    }

    .details-grid {
        display: grid;
        grid-template-columns: 1fr 1fr;
        gap: 16px 15px;
        margin-bottom: 25px;
    }

    .detail-item label {
        display: block;
        font-size: 10px;
        font-weight: 800;
        color: #64748b !important;
        text-transform: uppercase;
        letter-spacing: 0.5px;
        margin-bottom: 2px;
    }

    .detail-item span {
        font-size: 14px;
        font-weight: 700;
        color: #0f172a !important;
    }

    .status-badge {
        display: inline-block;
        padding: 4px 10px;
        border-radius: 6px;
        font-weight: 800;
        font-size: 11px;
        text-transform: uppercase;
    }
    
    .badge-menunggu {
        background: #d1fae5 !important;
        color: #065f46 !important;
    }

    .badge-proses {
        background: #dbeafe !important;
        color: #1e40af !important;
        animation: pulse 2s infinite;
    }

    @keyframes pulse {
        0%, 100% { opacity: 1; }
        50% { opacity: 0.6; }
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
        transition: all 0.2s;
    }

    .btn-save { background: #10b981; color: white; }
    .btn-save:hover { background: #059669; }
    .btn-dashboard { background: #f1f5f9; color: #475569; border: 1px solid #e2e8f0; }
    .btn-dashboard:hover { background: #e2e8f0; }

    .ticket-footer {
        text-align: center;
        font-size: 11px;
        color: #64748b !important;
        margin-top: 20px;
        line-height: 1.6;
        border-top: 1px dashed #e2e8f0;
        padding-top: 15px;
    }
</style>

{{-- LOGIKA RAMAH OPERASIONAL & PENENTUAN BAHASA HUMANIS --}}
@php
    $waktuDaftar = \Carbon\Carbon::parse($data->created_at);
    $jamMenitDaftar = $waktuDaftar->format('H:i');
    $waktuSekarang = \Carbon\Carbon::now();
    
    $namaDokter = 'Dokter Tidak Diketahui';
    $jamPraktek = '-';
    $catatanEdukasi = null;
    
    $poliClean = strtolower($data->poli);

    if (str_contains($poliClean, 'gigi')) {
        $namaDokter = 'drg. Affrida Wahyu K.D';
        $jamPraktek = '08.00 – 12.00';
        $jamMulai = '08.00';
        $jamSelesai = '12:00';
        
        if ($waktuSekarang->format('H:i') > $jamSelesai) {
            $catatanEdukasi = "<b>Jam Operasional Selesai</b> 🌙<br>Mohon maaf, saat ini jam pelayanan aktif untuk sesi hari ini telah berakhir. Antrean Anda otomatis akan dijadwalkan untuk dilayani esok hari mulai pukul <b>{$jamMulai} WIB</b>. Harap menjaga kesehatan Anda.";
        } elseif ($waktuSekarang->format('H:i') < '08:00') {
            $catatanEdukasi = "<b>Selamat Pagi, {$data->nama_pasien}!</b> 👋<br>Saat ini loket pemeriksaan belum dimulai. Layanan tatap muka bersama <b>{$namaDokter}</b> akan dibuka secara resmi tepat pukul <b>{$jamMulai} WIB</b>.<br><br><b>Tips Nyaman:</b> Anda tidak perlu terburu-buru ke klinik sekarang. Silakan datang ke ruang tunggu 15 menit sebelum jam pelayanan dimulai.";
        }
    } elseif (str_contains($poliClean, 'kia') || str_contains($poliClean, 'kb')) {
        if ($jamMenitDaftar < '11:30') {
            $namaDokter = 'Dita Sevi A, S.Tr. Keb';
            $jamPraktek = '07.00 – 11.30';
            $jamMulai = '07.00';
            $jamSelesai = '11:30';
        } else {
            $namaDokter = 'Nailis A, S.Tr. Keb., Bdn';
            $jamPraktek = '11.30 – 15.30';
            $jamMulai = '11.30';
            $jamSelesai = '15:30';
        }
        
        if ($waktuSekarang->format('H:i') > $jamSelesai) {
            $catatanEdukasi = "<b>Jam Operasional Selesai</b> 🌙<br>Mohon maaf, pelayanan aktif di Poli KIA & KB untuk hari ini sudah ditutup. Antrean Anda akan diprioritaskan esok hari mulai pukul <b>07.00 WIB</b>.";
        } elseif ($waktuSekarang->format('H:i') < $jamMulai) {
            $catatanEdukasi = "<b>Selamat Pagi, {$data->nama_pasien}!</b> 👋<br>Layanan tatap muka bersama bidan pemeriksa baru akan dibuka secara resmi pukul <b>{$jamMulai} WIB</b>. Silakan bersantai sejenak, nomor antrean Anda sudah aman tersimpan di sistem kami.";
        }
    } else {
        // Default Poli Umum
        if ($jamMenitDaftar < '11:30') {
            $namaDokter = 'dr. Ahmad Syaikudin';
            $jamPraktek = '07.00 – 11.30';
            $jamMulai = '07.00';
            $jamSelesai = '11:30';
        } else {
            $namaDokter = 'dr. Ferry Eko Santoso';
            $jamPraktek = '11.30 – 15.30';
            $jamMulai = '11.30';
            $jamSelesai = '15:30';
        }

        if ($waktuSekarang->format('H:i') > $jamSelesai) {
            $catatanEdukasi = "<b>Jam Operasional Selesai</b> 🌙<br>Poli Umum telah menyelesaikan sesi praktik dokter hari ini. Loket pemeriksaan fisik rekam medis akan dibuka kembali besok pagi mulai pukul <b>07.00 WIB</b>.";
        } elseif ($jamMenitDaftar >= '11:30' && $waktuSekarang->format('H:i') < '11:30') {
            $catatanEdukasi = "Halo <b>{$data->nama_pasien}</b>, Anda terdaftar untuk pelayanan sesi siang bersama <b>{$namaDokter}</b>. Jam kerja dokter dimulai pukul <b>11.30 WIB</b>. Harap kembali ke ruang tunggu saat jam praktik dimulai ya!";
        } elseif ($waktuSekarang->format('H:i') < $jamMulai) {
            $catatanEdukasi = "<b>Selamat Pagi, {$data->nama_pasien}!</b> 👋<br>Poli Umum belum membuka jam operasional pelayanan. <b>{$namaDokter}</b> akan mulai melayani pasien tepat pukul <b>{$jamMulai} WIB</b>.";
        }
    }
@endphp

<div class="antrian-wrapper">
    
    {{-- HANYA SATU KARTU TIKET MURNI TANPA MENUMPUK --}}
    <div class="integrated-ticket" id="capture-zone">
        
        {{-- Sesi Atas Tiket --}}
        <div class="ticket-header">
            <div class="hospital-identity font-black">POLKES JOMBANG</div>
            <div style="color: #64748b; font-weight: 700; font-size: 11px; letter-spacing: 1.5px; margin-top: 4px;">NOMOR ANTRIAN DOKTER</div>
            <div class="ticket-display-number">
                {{ str_pad($data->nomor_antrian, 2, '0', STR_PAD_LEFT) }}
            </div>
            <div style="color: #334155; font-weight: 600; font-size: 13px;">
                {{ $waktuDaftar->translatedFormat('l, d F Y') }}
            </div>
        </div>

        {{-- Efek Sobekan Tiket --}}
        <div class="ticket-tear-line"></div>

        {{-- Sesi Bawah Tiket --}}
        <div class="ticket-body">
            <div class="details-grid">
                <div class="detail-item col-span-2" style="grid-column: span 2;">
                    <label>Nama Pasien</label>
                    <span class="text-base font-extrabold text-slate-800">{{ $data->nama_pasien }}</span>
                </div>
                
                <div class="detail-item">
                    <label>Layanan Unit</label>
                    <span class="text-emerald-600 font-extrabold">{{ $data->poli }}</span>
                </div>

                <div class="detail-item">
                    <label>Status</label>
                    <div>
                        @if($data->status === 'diproses_dokter')
                            <span class="status-badge badge-proses">Dipanggil</span>
                        @else
                            <span class="status-badge badge-menunggu">Menunggu</span>
                        @endif
                    </div>
                </div>

                <div class="detail-item col-span-2 border-t border-b border-slate-100 py-3 my-1" style="grid-column: span 2;">
                    <label>Dokter / Pemeriksa</label>
                    <span class="text-slate-900 font-black block text-base leading-tight">{{ $namaDokter }}</span>
                    <span class="text-xs text-slate-500 font-semibold block mt-1">Sesi Kerja: {{ $jamPraktek }} WIB</span>
                </div>

                <div class="detail-item">
                    <label>Antrean Se-Dokter</label>
                    <span class="font-mono font-black text-slate-800 text-base">
                        {{ $data->status === 'diproses_dokter' ? '0' : $antrianDiDepan }} Orang
                    </span>
                </div>
                
                <div class="detail-item">
                    <label>Waktu Daftar</label>
                    <span class="font-mono text-slate-700 font-bold">{{ $waktuDaftar->format('H:i') }} WIB</span>
                </div>

                <div class="detail-item col-span-2 bg-emerald-50 p-3.5 rounded-xl border border-emerald-100" style="grid-column: span 2;">
                    <label class="text-emerald-800">Estimasi Dilayani</label>
                    <span class="text-base font-black text-emerald-700 tracking-wide block mt-0.5">{{ $prediksi }}</span>
                </div>

                {{-- NOTIFIKASI EDUKASI DENGAN RENDER HTML YANG RAMAH DAN HUMANIS --}}
                @if($catatanEdukasi)
                <div class="detail-item col-span-2 bg-amber-50 p-3.5 rounded-xl border border-amber-200" style="grid-column: span 2;">
                    <label class="text-amber-800 font-black flex items-center gap-1">
                        ⚠️ KETERANGAN OPERASIONAL
                    </label>
                    <p class="text-xs text-amber-900 font-medium leading-relaxed mt-1.5">
                        {!! $catatanEdukasi !!}
                    </p>
                </div>
                @endif
            </div>

            <!-- Tombol Aksi -->
            <div class="no-screenshot mt-4">
                <button onclick="saveTicket()" class="btn-action btn-save shadow-md">
                    SIMPAN ANTRIAN KE GALERI
                </button>
                <a href="{{ route('dashboard') }}" class="btn-action btn-dashboard">
                    KEMBALI KE DASHBOARD
                </a>
            </div>

            <div class="ticket-footer">
                Silakan datang ke area tunggu klinik 15 menit sebelum waktu estimasi.<br>
                Halaman dapat di-refresh berkala untuk pembaharuan sisa antrean.
            </div>
        </div>
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
            logging: false
        }).then(canvas => {
            noShow.style.display = 'block';
            try {
                const dataUrl = canvas.toDataURL('image/png', 1.0);
                const link = document.createElement('a');
                const namaFileDokter = "{{ $namaDokter }}".replace(/[^a-zA-Z0-9]/g, "_");
                
                link.download = `Antrian_${namaFileDokter}_{{ $data->nomor_antrian }}.png`;
                link.href = dataUrl;
                
                document.body.appendChild(link);
                link.click();
                document.body.removeChild(link);
            } catch (error) {
                alert('Gagal mengunduh gambar: ' + error.message);
                noShow.style.display = 'block';
            }
        }).catch(err => {
            alert('Terjadi kesalahan saat memproses gambar.');
            noShow.style.display = 'block';
        });
    }
</script>

@endsection