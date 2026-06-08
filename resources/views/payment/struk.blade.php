<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Struk Pembayaran - {{ $pembayaran->payment_ref }}</title>
    
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Courier+Prime:ital,wght@0,400;0,700;1,400&display=swap" rel="stylesheet">

    {{-- LIBRARY UNTUK CONVERT HTML KE PDF --}}
    <script src="https://cdnjs.cloudflare.com/ajax/libs/html2pdf.js/0.10.1/html2pdf.bundle.min.js"></script>

    <style>
        body { font-family: 'Courier Prime', monospace; color: #000; background-color: #e2e8f0; }
        .receipt-container { max-width: 420px; margin: 40px auto; background: #fff; padding: 30px; box-shadow: 0 10px 25px rgba(0,0,0,0.1); }
        .dashed-line { border-top: 2px dashed #000000; margin: 15px 0; }
    </style>
</head>
<body>

    <div class="max-w-md mx-auto mt-6 flex justify-between px-4">
        <a href="/dashboard" class="px-4 py-2 bg-slate-800 text-white text-sm font-bold rounded-lg hover:bg-slate-700 transition-all">
            <i class="fa-solid fa-arrow-left mr-2"></i> Kembali
        </a>
        <button onclick="downloadPDF()" class="px-4 py-2 bg-emerald-600 text-white text-sm font-bold rounded-lg hover:bg-emerald-500 transition-all cursor-pointer">
            <i class="fa-solid fa-download mr-2"></i> Download Ulang PDF
        </button>
    </div>

    {{-- KERTAS STRUK --}}
    <div id="kertas-struk" class="receipt-container">
        
        <div class="text-center mb-4">
            <h1 class="text-xl font-bold uppercase tracking-wide">POLKES 05.09.15 JOMBANG</h1>
            <p class="text-xs mt-1">Jl. KH. Wahid Hasyim No.28 B<br>Jombang, Jawa Timur</p>
            <p class="text-xs">Telp: (0877) 7723-5386</p>
        </div>

        <div class="dashed-line"></div>

        <div class="text-xs space-y-1">
            <div class="flex justify-between">
                <span>TANGGAL</span>
                <span>{{ \Carbon\Carbon::parse($pembayaran->updated_at)->format('d/m/Y H:i') }} WIB</span>
            </div>
            <div class="flex justify-between">
                <span>INVOICE</span>
                <span>{{ $pembayaran->payment_ref }}</span>
            </div>
            <div class="flex justify-between">
                <span>METODE</span>
                <span class="uppercase">{{ $pembayaran->metode }}</span>
            </div>
        </div>

        <div class="dashed-line"></div>

        <div class="text-xs space-y-1">
            <div class="flex justify-between">
                <span>PASIEN:</span>
                <span class="font-bold text-right uppercase">{{ $pembayaran->pendaftaran->nama_pasien ?? '-' }}</span>
            </div>
            <div class="flex justify-between">
                <span>POLI:</span>
                <span class="text-right uppercase">{{ $pembayaran->pendaftaran->poli ?? '-' }}</span>
            </div>
        </div>

        <div class="dashed-line"></div>

        <div class="text-xs space-y-3">
            @php
                $biayaDokter = (int) ($pembayaran->biaya_dokter ?? 0);
                $biayaAdmin  = (int) ($pembayaran->biaya_admin ?? 0);
                $biayaObat   = (int) ($pembayaran->total_obat ?? 0);

                $totalFinal = $biayaDokter + $biayaAdmin + $biayaObat;
            @endphp
            
            <div class="flex justify-between items-start">
                <div>
                    <p class="font-bold">JASA DOKTER & KONSULTASI</p>
                    <p class="text-[10px] text-slate-500">Pemeriksaan medis dasar poli</p>
                </div>
                <span>Rp {{ number_format($biayaDokter, 0, ',', '.') }}</span>
            </div>
            
            <div class="flex justify-between items-start">
                <div>
                    <p class="font-bold">ADMINISTRASI RUMAH SAKIT</p>
                    <p class="text-[10px] text-slate-500">Pencatatan rekam medis digital</p>
                </div>
                <span>Rp {{ number_format($biayaAdmin, 0, ',', '.') }}</span>
            </div>

            <div class="pt-1">
                <p class="font-bold mb-1">RINCIAN FARMASI / OBAT:</p>
                <div class="space-y-2 pl-2">
                    @forelse($rincianObat as $obat)
                        <div class="flex justify-between items-end">
                            <div class="leading-tight">
                                <p>• {{ $obat['nama'] }}</p>
                                @if($obat['harga'] > 0)
                                    <p class="text-[10px] text-slate-500">&nbsp;&nbsp;({{ $obat['qty'] }} pesanan x Rp {{ number_format($obat['harga'], 0, ',', '.') }})</p>
                                @endif
                            </div>
                            <span class="text-right">Rp {{ number_format($obat['total'], 0, ',', '.') }}</span>
                        </div>
                    @empty
                        <p class="text-slate-500 italic">• Tidak ada rincian item obat</p>
                    @endforelse
                </div>
            </div>
        </div>

        <div class="dashed-line"></div>

        <div class="flex justify-between items-center text-base font-bold">
            <span>TOTAL BERSIH</span>
            <span>Rp {{ number_format($totalFinal, 0, ',', '.') }}</span>
        </div>
        
        <div class="text-center text-xs font-bold mt-4">
            STATUS PEMBAYARAN: <span class="border border-black px-2 py-0.5 bg-black text-white rounded uppercase">LUNAS</span>
        </div>

        <div class="dashed-line"></div>

        <div class="text-center text-[11px] space-y-1">
            <p>Terima kasih atas kunjungan Anda.</p>
            <p>Semoga Anda lekas sembuh!</p>
            <p class="text-[9px] pt-3 opacity-40">Nota bukti sah diterbitkan oleh sistem cloud</p>
        </div>
    </div>

    <script>
        function downloadPDF() {
            const element = document.getElementById('kertas-struk');
            const opt = {
                margin: 0.3,
                filename: 'Struk_{{ $pembayaran->payment_ref }}.pdf',
                image: { type: 'jpeg', quality: 0.98 },
                html2canvas: { scale: 2.5 },
                jsPDF: { unit: 'in', format: 'letter', orientation: 'portrait' }
            };
            html2pdf().set(opt).from(element).save();
        }

        window.onload = function() {
            setTimeout(function() { downloadPDF(); }, 500); 
        }
    </script>
</body>
</html>