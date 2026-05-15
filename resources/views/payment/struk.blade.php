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
        body {
            font-family: 'Courier Prime', monospace;
            color: #000;
            background-color: #e2e8f0;
        }

        /* Area ini yang akan di-convert jadi PDF */
        .receipt-container {
            max-width: 400px;
            margin: 40px auto;
            background: #fff;
            padding: 30px;
            box-shadow: 0 10px 25px rgba(0,0,0,0.1);
        }

        .dashed-line {
            border-top: 2px dashed #cbd5e1;
            margin: 20px 0;
        }
    </style>
</head>
<body>

    {{-- Tombol Kembali & Manual Download --}}
    <div class="max-w-md mx-auto mt-6 flex justify-between px-4">
        <a href="/dashboard" class="px-4 py-2 bg-slate-800 text-white text-sm font-bold rounded-lg hover:bg-slate-700 transition-all">
            <i class="fa-solid fa-arrow-left mr-2"></i> Kembali
        </a>
        
        <button onclick="downloadPDF()" class="px-4 py-2 bg-emerald-600 text-white text-sm font-bold rounded-lg hover:bg-emerald-500 transition-all">
            <i class="fa-solid fa-download mr-2"></i> Download Ulang PDF
        </button>
    </div>

    {{-- KERTAS STRUK (Berikan ID agar mudah ditarget oleh html2pdf) --}}
    <div id="kertas-struk" class="receipt-container">
        
        {{-- HEADER STRUK --}}
        <div class="text-center mb-6">
            <h1 class="text-xl font-bold uppercase tracking-widest">POLKES JOMBANG</h1>
            <p class="text-sm mt-1">Jl. KH. Wahid Hasyim No.133<br>Jombang, Jawa Timur</p>
            <p class="text-sm">Telp: (0321) 123456</p>
        </div>

        <div class="dashed-line"></div>

        {{-- INFO TRANSAKSI --}}
        <div class="text-sm space-y-2 mb-6">
            <div class="flex justify-between">
                <span>TANGGAL</span>
                <span>{{ \Carbon\Carbon::parse($pembayaran->updated_at)->format('d/m/Y H:i') }}</span>
            </div>
            <div class="flex justify-between">
                <span>INVOICE</span>
                <span>{{ $pembayaran->payment_ref }}</span>
            </div>
            <div class="flex justify-between">
                <span>KASIR</span>
                <span>Sistem (Midtrans)</span>
            </div>
        </div>

        <div class="dashed-line"></div>

        {{-- DETAIL PASIEN --}}
        <div class="text-sm space-y-2 mb-6">
            <div class="flex justify-between">
                <span>PASIEN</span>
                <span class="font-bold text-right">{{ $pembayaran->pendaftaran->nama_pasien ?? '-' }}</span>
            </div>
            <div class="flex justify-between">
                <span>POLI</span>
                <span class="text-right">{{ $pembayaran->pendaftaran->poli ?? '-' }}</span>
            </div>
        </div>

        <div class="dashed-line"></div>

        {{-- RINCIAN BIAYA --}}
        <div class="text-sm space-y-2">
            <div class="flex justify-between">
                <span>Biaya Admin</span>
                <span>Rp {{ number_format($pembayaran->biaya_admin ?? 10000, 0, ',', '.') }}</span>
            </div>
            <div class="flex justify-between">
                <span>Biaya Dokter</span>
                <span>Rp {{ number_format($pembayaran->biaya_dokter ?? 50000, 0, ',', '.') }}</span>
            </div>
            <div class="flex justify-between">
                <span>Biaya Obat</span>
                <span>Rp {{ number_format($pembayaran->total_obat ?? 0, 0, ',', '.') }}</span>
            </div>
        </div>

        <div class="dashed-line"></div>

        {{-- TOTAL AKHIR --}}
        <div class="flex justify-between items-center text-lg font-bold mb-6">
            <span>TOTAL</span>
            <span>Rp {{ number_format($pembayaran->total_biaya, 0, ',', '.') }}</span>
        </div>
        
        <div class="text-center text-sm font-bold mb-6">
            STATUS: <span class="uppercase">LUNAS</span>
        </div>

        <div class="dashed-line"></div>

        {{-- FOOTER --}}
        <div class="text-center text-xs mt-6">
            <p>Terima kasih atas kunjungan Anda.</p>
            <p>Semoga lekas sembuh!</p>
            <p class="mt-4 opacity-50">Di-generate otomatis oleh sistem</p>
        </div>

    </div>

    {{-- SCRIPT OTOMATIS DOWNLOAD PDF --}}
    <script>
        // Fungsi untuk mengubah elemen HTML menjadi PDF dan mendownloadnya
        function downloadPDF() {
            // Ambil elemen yang mau dijadikan PDF (hanya kotak putih struknya saja)
            const element = document.getElementById('kertas-struk');
            
            // Konfigurasi PDF
            const opt = {
                margin:       0.5,
                filename:     'Struk_{{ $pembayaran->payment_ref }}.pdf',
                image:        { type: 'jpeg', quality: 0.98 },
                html2canvas:  { scale: 2 },
                jsPDF:        { unit: 'in', format: 'a5', orientation: 'portrait' }
            };

            // Proses Download
            html2pdf().set(opt).from(element).save();
        }

        // Jalankan otomatis 0.5 detik setelah halaman terbuka
        window.onload = function() {
            setTimeout(function() {
                downloadPDF();
            }, 500); 
        }
    </script>
</body>
</html>