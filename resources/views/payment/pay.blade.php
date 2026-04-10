<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Pembayaran - Polkes Jombang</title>

    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" rel="stylesheet">

    {{-- MIDTRANS SNAP --}}
    <script 
        src="https://app.sandbox.midtrans.com/snap/snap.js"
        data-client-key="{{ config('services.midtrans.client_key') }}">
    </script>
</head>

<body class="bg-slate-50 font-sans">

<div class="min-h-screen flex flex-col items-center justify-center p-6">

    {{-- BACK LINK --}}
    <a href="/dashboard" class="mb-6 flex items-center gap-2 text-slate-400 hover:text-emerald-600 font-medium text-sm transition-colors">
        <i class="fa-solid fa-arrow-left"></i>
        Kembali ke Dashboard
    </a>

    <div class="max-w-md w-full bg-white rounded-3xl shadow-xl overflow-hidden border border-gray-100">

        {{-- HEADER --}}
        <div class="bg-emerald-600 p-8 text-center text-white relative">
            <div class="absolute top-0 right-0 p-4 opacity-10">
                <i class="fa-solid fa-file-invoice-dollar text-6xl"></i>
            </div>
            <h2 class="text-xl font-bold uppercase tracking-wider">Pembayaran Pasien</h2>
            <p class="text-[10px] mt-2 opacity-80 font-mono bg-emerald-700/50 inline-block px-3 py-1 rounded-full uppercase">
                REF: {{ $pembayaran->payment_ref ?? '-' }}
            </p>
        </div>

        <div class="p-8">

            {{-- TOTAL TAGIHAN --}}
            <div class="text-center mb-8">
                <p class="text-gray-400 text-[10px] font-bold uppercase tracking-widest mb-1">TOTAL TAGIHAN</p>
                <h1 class="text-4xl font-extrabold text-slate-900 leading-none">
                    <span class="text-emerald-600 text-xl mr-1 italic">Rp</span>{{ number_format((int) str_replace(['.', ','], '', $pembayaran->total_biaya), 0, ',', '.') }}
                </h1>
            </div>

            {{-- DETAIL INFORMASI --}}
            <div class="mb-8 p-5 bg-slate-50 rounded-2xl space-y-4 text-sm border border-slate-100">
                <div class="flex justify-between items-center border-b border-slate-200 pb-3">
                    <span class="text-gray-500 font-medium italic">Nama Pasien</span>
                    <span class="font-bold text-slate-800 uppercase text-xs">
                        {{ $pembayaran->pendaftaran->nama_pasien ?? (Auth::check() ? Auth::user()->name : 'Pasien Umum') }}
                    </span>
                </div>
                <div class="flex justify-between items-center italic">
                    <span class="text-gray-500 font-medium">Status</span>
                    @if($pembayaran->status === 'lunas')
                        <span class="px-3 py-1 rounded-full text-[9px] font-black uppercase bg-emerald-100 text-emerald-700 border border-emerald-200">
                            TERBAYAR
                        </span>
                    @else
                        <span class="px-3 py-1 rounded-full text-[9px] font-black uppercase bg-orange-100 text-orange-700 border border-orange-200">
                            MENUNGGU PEMBAYARAN
                        </span>
                    @endif
                </div>
            </div>

            {{-- ACTION BUTTON --}}
            @if($pembayaran->status !== 'lunas')
                <button id="pay-button"
                    class="w-full bg-emerald-600 hover:bg-slate-900 active:scale-95 text-white py-4 rounded-2xl font-black uppercase tracking-widest shadow-lg shadow-emerald-200 transition-all flex justify-center items-center gap-3">
                    <i class="fa-solid fa-shield-check text-lg"></i>
                    Bayar Sekarang
                </button>
            @else
                <div class="flex flex-col items-center justify-center p-6 bg-emerald-50 rounded-2xl border border-emerald-100 text-emerald-700">
                    <div class="w-12 h-12 bg-emerald-600 text-white rounded-full flex items-center justify-center mb-3 shadow-lg shadow-emerald-200">
                        <i class="fa-solid fa-check text-xl"></i>
                    </div>
                    <span class="font-black uppercase tracking-tighter">Pembayaran Sukses</span>
                    <p class="text-[10px] opacity-70 mt-1 italic font-medium">Transaksi Anda telah berhasil diverifikasi sistem.</p>
                </div>
            @endif

            {{-- FOOTER LOGOS (MULTI-PAYMENT SUPPORTED) --}}
            <div class="mt-10 pt-6 border-t border-slate-100 flex flex-col items-center gap-4">
                <p class="text-[9px] text-slate-400 font-bold uppercase tracking-[0.2em]">Metode Pembayaran Didukung</p>
                
                <div class="flex flex-wrap justify-center items-center gap-5 opacity-50 grayscale hover:grayscale-0 transition-all duration-500">
                    {{-- QRIS --}}
                    <img src="https://api.qrserver.com/v1/create-qr-code/?data=QRIS&size=50x20&bgcolor=ffffff&color=000000" 
                         alt="QRIS" class="h-5 hidden"> 
                    <span class="text-[10px] font-black text-slate-400 border border-slate-300 px-2 py-0.5 rounded">QRIS</span>

                    {{-- Link Gambar Alternatif yang lebih stabil --}}
                    <img src="https://static.midtrans.com/logo/midtrans-color-svg.svg" alt="Midtrans" class="h-4">
                    
                    {{-- ATM Bersama / GPN Style --}}
                    <span class="text-[10px] font-black text-slate-400 border border-slate-300 px-2 py-0.5 rounded">ATM BERSAMA</span>
                    
                    {{-- Visa/Mastercard --}}
                    <div class="flex gap-2">
                        <i class="fa-brands fa-cc-visa text-xl text-slate-400"></i>
                        <i class="fa-brands fa-cc-mastercard text-xl text-slate-400"></i>
                    </div>
                </div>
                
                <p class="text-[8px] text-slate-300 font-medium italic">Secure payment powered by Midtrans Snap</p>
            </div>

        </div>
    </div>
</div>

{{-- JAVASCRIPT LOGIC --}}
<script>
    const snapToken = @json($snapToken);
    const btn = document.getElementById('pay-button');

    if (btn) {
        btn.addEventListener('click', function(e) {
            e.preventDefault();

            if (!snapToken) {
                alert("Token pembayaran tidak ditemukan. Silakan refresh halaman.");
                return;
            }

            if (typeof window.snap === "undefined") {
                alert("Sistem pembayaran gagal dimuat. Periksa koneksi internet Anda.");
                return;
            }

            // Loading State
            btn.innerHTML = '<i class="fa-solid fa-spinner fa-spin mr-2"></i> Membuka Kasir...';
            btn.classList.add('opacity-70', 'cursor-not-allowed');
            btn.disabled = true;

            window.snap.pay(snapToken, {
                onSuccess: function(result) {
                    console.log("SUCCESS:", result);
                    window.location.reload(); 
                },
                onPending: function(result) {
                    console.log("PENDING:", result);
                    window.location.reload();
                },
                onError: function(result) {
                    console.log("ERROR:", result);
                    alert("Terjadi kesalahan saat memproses pembayaran.");
                    resetBtn();
                },
                onClose: function() {
                    console.log("Customer closed the popup");
                    resetBtn();
                }
            });

            function resetBtn() {
                btn.innerHTML = '<i class="fa-solid fa-shield-check"></i> Bayar Sekarang';
                btn.classList.remove('opacity-70', 'cursor-not-allowed');
                btn.disabled = false;
            }
        });
    }
</script>

</body>
</html>