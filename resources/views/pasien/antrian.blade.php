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
        padding: 35px 20px;
        text-align: center;
        border-bottom: 2px dashed #cbd5e1;
        position: relative;
    }

    .ticket-header::before, .ticket-header::after {
        content: "";
        position: absolute;
        width: 30px;
        height: 30px;
        background: #081d18; 
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
        letter-spacing: 0.5px;
    }

    .ticket-display-number {
        font-size: 80px;
        font-weight: 900;
        color: #1e293b !important;
        line-height: 1;
        margin: 12px 0;
        letter-spacing: -2px;
    }

    .ticket-body {
        background: #ffffff !important;
        padding: 25px 30px;
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

@php
    $waktuDaftar = \Carbon\Carbon::parse($data->created_at);
    $jamMenitDaftar = $waktuDaftar->format('H:i');
    $waktuSekarang = \Carbon\Carbon::now();
    $jamSekarang = $waktuSekarang->format('H:i');
    
    $dokterTerpilih = $data->nama_dokter ? strtolower($data->nama_dokter) : '';
    $dokterId = $data->dokter_id ?? $data->id_dokter ?? null; 
    
    $namaDokter = 'Dokter Tidak Diketahui';
    $jamPraktek = '-';
    $jamMulai = '00:00';
    $jamSelesai = '24:00';
    $catatanEdukasi = null;
    
    $poliClean = strtolower($data->poli);

    if (str_contains($poliClean, 'gigi') || str_contains($dokterTerpilih, 'affrida') || $dokterId == 3) {
        $namaDokter = 'drg. Affrida Wahyu K.D';
        $jamPraktek = '08.00 – 12.00';
        $jamMulai = '08:00';
        $jamSelesai = '12:00';
    } elseif (str_contains($poliClean, 'kia') || str_contains($poliClean, 'kb')) {
        if (str_contains($dokterTerpilih, 'dita') || $dokterId == 4 || $dokterId == '4') {
            $namaDokter = 'Dita Sevi A, S.Tr. Keb';
            $jamPraktek = '07.00 – 11.30';
            $jamMulai = '07:00';
            $jamSelesai = '11:30';
        } else {
            $namaDokter = 'Nailis A, S.Tr. Keb., Bdn';
            $jamPraktek = '11.30 – 15.30';
            $jamMulai = '11:30';
            $jamSelesai = '15:30';
        }
    } else {
        if (str_contains($dokterTerpilih, 'ferry') || $dokterId == 28 || $dokterId == '28' || $dokterId == 2) {
            $namaDokter = 'dr. Ferry Eko Santoso';
            $jamPraktek = '11.30 – 15.30';
            $jamMulai = '11:30';
            $jamSelesai = '15:30';
        } else {
            $namaDokter = 'dr. Ahmad Syaikudin';
            $jamPraktek = '07.00 – 11.30';
            $jamMulai = '07:00';
            $jamSelesai = '11:30';
        }
    }

    $antrianSpesifikDokter = \App\Models\PendaftaranPoli::whereDate('created_at', $waktuDaftar->toDateString())
        ->whereIn('status', ['menunggu', 'menunggu_petugas'])
        ->where('id', '<', $data->id)
        ->where(function($query) use ($namaDokter, $data, $dokterId) {
            $query->where('nama_dokter', $namaDokter)
                  ->orWhere('dokter_id', $dokterId)
                  ->orWhere(function($sub) use ($data) {
                      $sub->whereNull('nama_dokter')->where('poli', $data->poli);
                  });
        })
        ->count();

    if ($jamSekarang > $jamSelesai) {
        $catatanEdukasi = "<b>Sesi Praktik Hari Ini Selesai</b><br>Jam kerja tatap muka {$namaDokter} telah berakhir. Antrean Anda akan dilayani esok hari mulai pukul <b>{$jamMulai} WIB</b>.";
    } elseif ($jamSekarang < $jamMulai) {
        $catatanEdukasi = "<b>Menunggu Jam Masuk Dokter</b><br>Jam praktik tatap muka bersama {$namaDokter} baru akan dimulai pukul <b>{$jamMulai} WIB</b>.";
    }
@endphp

<div class="antrian-wrapper">
    <div class="integrated-ticket" id="capture-zone">
        <div class="ticket-header">
            <div class="hospital-identity">POLKES JOMBANG</div>
            <div style="color: #64748b; font-weight: 700; font-size: 11px; letter-spacing: 1px; margin-top: 4px;">NOMOR ANTRIAN DOKTER</div>
            <div class="ticket-display-number">
                {{ str_pad($data->nomor_antrian, 2, '0', STR_PAD_LEFT) }}
            </div>
            <div style="color: #334155; font-weight: 600; font-size: 14px;">
                {{ $waktuDaftar->translatedFormat('l, d F Y') }}
            </div>
        </div>

        <div class="ticket-body">
            <div class="details-grid">
                <div class="detail-item col-span-2" style="grid-column: span 2;">
                    <label>Nama Pasien</label>
                    <span class="text-base font-extrabold">{{ $data->nama_pasien }}</span>
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

                <div class="detail-item col-span-2 border-t border-b border-slate-100 py-2.5" style="grid-column: span 2;">
                    <label>Dokter / Pemeriksa</label>
                    <span class="text-slate-900 font-black block text-base">{{ $namaDokter }}</span>
                    <span class="text-xs text-slate-500 font-semibold block mt-0.5">Jam Kerja Sesi: {{ $jamPraktek }} WIB</span>
                </div>

                <div class="detail-item">
                    <label>Antrean Se-Dokter</label>
                    <span class="font-mono font-black text-slate-800">
                        {{ $data->status === 'diproses_dokter' ? '0' : $antrianSpesifikDokter }} Orang Lagi
                    </span>
                </div>
                
                <div class="detail-item">
                    <label>Waktu Daftar</label>
                    <span class="font-mono text-slate-700 font-bold">{{ $waktuDaftar->format('H:i') }} WIB</span>
                </div>

                <div class="detail-item col-span-2 bg-emerald-50 p-3 rounded-xl border border-emerald-100" style="grid-column: span 2;">
                    <label class="text-emerald-800">Estimasi Dilayani</label>
                    <span class="text-base font-black text-emerald-700 tracking-wide block">{{ $prediksi }}</span>
                </div>

                @if($catatanEdukasi)
                <div class="detail-item col-span-2 bg-amber-50 p-3 rounded-xl border border-amber-200" style="grid-column: span 2;">
                    <label class="text-amber-800 font-black flex items-center gap-1">
                        ⚠️ KETERANGAN OPERASIONAL
                    </label>
                    <p class="text-xs text-amber-900 font-medium leading-relaxed mt-1">
                        {!! $catatanEdukasi !!}
                    </p>
                </div>
                @endif
            </div>

            <div class="no-screenshot">
                <button onclick="saveTicket()" class="btn-action btn-save">
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