<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Struk Pembayaran - {{ $pembayaran->payment_ref }}</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Courier+Prime:ital,wght@0,400;0,700;1,400&display=swap" rel="stylesheet">
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
            <i class="fa-solid fa-download mr-2"></i> Download PDF
        </button>
    </div>

    <div id="kertas-struk" class="receipt-container">
        <div class="text-center mb-4">
            <h1 class="text-xl font-bold uppercase tracking-wide">POLKES 05.09.15 JOMBANG</h1>
            <p class="text-xs mt-1">Jl. KH. Wahid Hasyim No.28 B<br>Jombang, Jawa Timur</p>
        </div>
        <div class="dashed-line"></div>

        <div class="text-xs space-y-1">
            <div class="flex justify-between"><span>TANGGAL</span><span>{{ \Carbon\Carbon::parse($pembayaran->updated_at)->format('d/m/Y H:i') }} WIB</span></div>
            <div class="flex justify-between"><span>INVOICE</span><span>{{ $pembayaran->payment_ref }}</span></div>
            <div class="flex justify-between"><span>METODE</span><span class="uppercase">{{ $pembayaran->metode }}</span></div>
        </div>

        <div class="dashed-line"></div>
        <div class="text-xs space-y-1">
            <div class="flex justify-between"><span>PASIEN:</span><span class="font-bold text-right uppercase">{{ $pembayaran->pendaftaran->nama_pasien ?? '-' }}</span></div>
            <div class="flex justify-between"><span>POLI:</span><span class="text-right uppercase">{{ $pembayaran->pendaftaran->poli ?? '-' }}</span></div>
        </div>

        <div class="dashed-line"></div>
        <div class="text-xs space-y-3">
            <div class="flex justify-between items-start">
                <div><p class="font-bold">JASA DOKTER</p></div>
                <span>Rp {{ number_format($biayaDokter ?? 0, 0, ',', '.') }}</span>
            </div>
            <div class="flex justify-between items-start">
                <div><p class="font-bold">ADMINISTRASI</p></div>
                <span>Rp {{ number_format($biayaAdmin ?? 0, 0, ',', '.') }}</span>
            </div>
            <div class="pt-1">
                <p class="font-bold mb-1">RINCIAN OBAT:</p>
                <div class="space-y-1 pl-2">
                    @forelse($rincianObat as $obat)
                        <div class="flex justify-between">
                            <span>• {{ $obat['nama'] }} ({{ $obat['qty'] }})</span>
                            <span>Rp {{ number_format($obat['total'], 0, ',', '.') }}</span>
                        </div>
                    @empty
                        <p class="text-slate-500 italic">• Tidak ada obat</p>
                    @endforelse
                </div>
            </div>
        </div>

        <div class="dashed-line"></div>
        <div class="flex justify-between items-center text-base font-bold">
            <span>TOTAL BERSIH</span>
            <span>Rp {{ number_format(($biayaDokter ?? 0) + ($biayaAdmin ?? 0) + ($pembayaran->total_obat ?? 0), 0, ',', '.') }}</span>
        </div>
        
        <div class="text-center text-xs font-bold mt-4">
            STATUS: <span class="uppercase">LUNAS</span>
        </div>
    </div>

    <script>
        function downloadPDF() {
            const element = document.getElementById('kertas-struk');
            html2pdf().set({ 
                margin: 0.3, 
                filename: 'Struk_{{ $pembayaran->payment_ref }}.pdf', 
                html2canvas: { scale: 2 },
                jsPDF: { unit: 'in', format: 'a4', orientation: 'portrait' } 
            }).from(element).save();
        }
    </script>
</body>
</html>