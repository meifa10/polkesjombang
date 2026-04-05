<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Pembayaran - Polkes Jombang</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
</head>
<body class="bg-slate-50">

<div class="min-h-screen flex flex-col items-center justify-center p-6">
    
    {{-- Tombol Kembali --}}
    <a href="/dashboard" class="mb-6 flex items-center gap-2 text-slate-400 hover:text-emerald-600 transition-colors group font-medium text-sm">
        <i class="fa-solid fa-arrow-left transition-transform group-hover:-translate-x-1"></i>
        Kembali ke Dashboard
    </a>

    <div class="max-w-md w-full bg-white rounded-[2.5rem] shadow-[0_20px_50px_rgba(0,0,0,0.04)] overflow-hidden border border-gray-100">
        
        {{-- Header Card --}}
        <div class="bg-gradient-to-br from-emerald-500 to-teal-700 p-10 text-center text-white relative">
            <div class="relative z-10">
                <div class="w-20 h-20 bg-white/20 backdrop-blur-md rounded-3xl flex items-center justify-center mx-auto mb-4 shadow-inner">
                    <i class="fa-solid fa-file-invoice-dollar text-3xl text-white"></i>
                </div>
                <h2 class="text-2xl font-bold tracking-tight">Pembayaran Pasien</h2>
                <p class="text-emerald-100 text-xs mt-2 uppercase tracking-[0.2em] font-medium opacity-80">
                    Ref: {{ $pembayaran->payment_ref }}
                </p>
            </div>
            <div class="absolute -top-12 -right-12 w-40 h-40 bg-white/10 rounded-full blur-2xl"></div>
            <div class="absolute -bottom-8 -left-8 w-24 h-24 bg-black/5 rounded-full blur-xl"></div>
        </div>

        <div class="p-10">
            {{-- Nominal Tagihan --}}
            <div class="text-center mb-10">
                <p class="text-gray-400 text-[10px] font-black uppercase tracking-[0.3em] mb-3">Total Tagihan</p>
                <h1 class="text-5xl font-black text-slate-800 tracking-tighter">
                    <span class="text-2xl font-bold mr-1 text-emerald-600">Rp</span>{{ number_format($pembayaran->total_biaya, 0, ',', '.') }}
                </h1>
            </div>

            {{-- Info Detail --}}
            <div class="bg-slate-50 rounded-[2rem] p-6 mb-8 border border-gray-100/50">
                <div class="flex justify-between items-center mb-4 pb-4 border-b border-gray-200/50">
                    <span class="text-gray-400 text-sm font-medium">Nama Pasien</span>
                    <span class="text-slate-700 font-bold text-sm">
                        {{ $pembayaran->pendaftaran->nama_pasien ?? Auth::user()->name }}
                    </span>
                </div>
                <div class="flex justify-between items-center">
                    <span class="text-gray-400 text-sm font-medium">Status</span>
                    <span class="px-4 py-1.5 {{ $pembayaran->status === 'lunas' ? 'bg-emerald-100 text-emerald-600' : 'bg-orange-100 text-orange-600' }} text-[10px] font-black rounded-full border border-current uppercase">
                        {{ str_replace('_', ' ', $pembayaran->status) }}
                    </span>
                </div>
            </div>

            {{-- Area Tombol / Status --}}
            <div class="space-y-4">
                @if($pembayaran->status === 'lunas')
                    <div class="bg-emerald-50 border border-emerald-100 p-6 rounded-2xl text-center">
                        <div class="w-12 h-12 bg-emerald-500 text-white rounded-full flex items-center justify-center mx-auto mb-3 shadow-lg shadow-emerald-200">
                            <i class="fa-solid fa-check text-xl"></i>
                        </div>
                        <p class="text-emerald-800 font-bold italic">Pembayaran Selesai</p>
                        <p class="text-emerald-600 text-xs mt-1">
                            Lunas pada {{ $pembayaran->tanggal_bayar ? \Carbon\Carbon::parse($pembayaran->tanggal_bayar)->format('d M Y H:i') : now()->format('d M Y H:i') }}
                        </p>
                    </div>
                    <a href="/dashboard" class="w-full flex items-center justify-center py-4 bg-slate-800 text-white rounded-2xl font-bold shadow-lg hover:bg-slate-900 transition-all active:scale-95">
                        Kembali ke Dashboard
                    </a>
                @else
                    <button id="pay-button" class="group w-full bg-emerald-600 hover:bg-emerald-700 text-white font-bold py-5 rounded-2xl shadow-[0_15px_30px_-5px_rgba(16,185,129,0.4)] transition-all duration-300 active:scale-[0.98] flex items-center justify-center gap-3">
                        <i class="fa-solid fa-credit-card group-hover:rotate-12 transition-transform"></i>
                        <span class="text-lg">Bayar Sekarang</span>
                    </button>
                    <a href="/dashboard" class="w-full inline-flex items-center justify-center py-4 text-slate-400 hover:text-slate-600 font-semibold text-sm transition-colors uppercase tracking-widest text-center">
                        Nanti Saja
                    </a>
                @endif
            </div>

            {{-- Footer Midtrans --}}
            <div class="mt-6 pt-8 border-t border-slate-50 flex flex-col items-center">
                <p class="text-[9px] font-bold text-slate-300 uppercase tracking-widest mb-3">Secure Payment by</p>
                <img src="https://upload.wikimedia.org/wikipedia/commons/thumb/b/ba/Logo_Midtrans.png/1200px-Logo_Midtrans.png" class="h-4 opacity-30 grayscale" alt="Midtrans">
            </div>
        </div>
    </div>
</div>

{{-- SCRIPT MIDTRANS --}}
<script src="https://app.sandbox.midtrans.com/snap/snap.js" data-client-key="{{ env('MIDTRANS_CLIENT_KEY') }}"></script>

<script>
    const btn = document.getElementById('pay-button');

    if (btn) {
        btn.onclick = function(e) {
            e.preventDefault();
            
            // Simpan konten asli tombol
            const originalContent = btn.innerHTML;
            
            // Ambil token dari backend Laravel
            const snapToken = "{{ $snapToken }}";

            // Validasi Token
            if (!snapToken || snapToken === "") {
                alert("Error: Snap Token tidak ditemukan. Silakan refresh halaman.");
                return;
            }

            // Update UI Tombol
            btn.innerHTML = '<i class="fa-solid fa-circle-notch animate-spin"></i> Menghubungkan...';
            btn.disabled = true;

            // Panggil Jendela Pembayaran Midtrans
            window.snap.pay(snapToken, {
                onSuccess: function(result) {
                    btn.innerHTML = '<i class="fa-solid fa-check"></i> Berhasil!';
                    // Tunggu agar webhook dari Midtrans memproses database
                    setTimeout(() => {
                        window.location.reload(); 
                    }, 2500);
                },
                onPending: function(result) {
                    alert("Pembayaran Anda sedang tertunda (Pending). Silakan selesaikan pembayaran.");
                    window.location.reload();
                },
                onError: function(result) {
                    console.error("Midtrans Error:", result);
                    alert("Terjadi kesalahan sistem saat memproses pembayaran.");
                    resetBtn(originalContent);
                },
                onClose: function() {
                    // Jika user menutup popup tanpa bayar
                    resetBtn(originalContent);
                }
            });

            function resetBtn(content) {
                btn.innerHTML = content;
                btn.disabled = false;
            }
        };
    }
</script>

</body>
</html>