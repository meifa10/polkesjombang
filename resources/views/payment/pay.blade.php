<script src="https://cdn.tailwindcss.com"></script>
<link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">

<div class="min-h-screen bg-slate-50 flex flex-col items-center justify-center p-6">
    
    <a href="/dashboard" class="mb-6 flex items-center gap-2 text-slate-400 hover:text-emerald-600 transition-colors group font-medium text-sm">
        <i class="fa-solid fa-arrow-left transition-transform group-hover:-translate-x-1"></i>
        Kembali ke Dashboard
    </a>

    <div class="max-w-md w-full bg-white rounded-[2.5rem] shadow-[0_20px_50px_rgba(0,0,0,0.04)] overflow-hidden border border-gray-100">
        
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
            <div class="text-center mb-10">
                <p class="text-gray-400 text-[10px] font-black uppercase tracking-[0.3em] mb-3">Total Tagihan Admin</p>
                <h1 class="text-5xl font-black text-slate-800 tracking-tighter">
                    <span class="text-2xl font-bold mr-1 text-emerald-600">Rp</span>{{ number_format($pembayaran->total_biaya, 0, ',', '.') }}
                </h1>
            </div>

            <div class="bg-slate-50 rounded-[2rem] p-6 mb-8 border border-gray-100/50">
                <div class="flex justify-between items-center mb-4 pb-4 border-b border-gray-200/50">
                    <span class="text-gray-400 text-sm font-medium">Pasien</span>
                    <span class="text-slate-700 font-bold text-sm">{{ Auth::user()->name }}</span>
                </div>
                <div class="flex justify-between items-center">
                    <span class="text-gray-400 text-sm font-medium">Status</span>
                    <span class="px-4 py-1.5 bg-emerald-50 text-emerald-600 text-[10px] font-black rounded-full border border-emerald-100 uppercase">
                        {{ str_replace('_', ' ', $pembayaran->status) }}
                    </span>
                </div>
            </div>

            <div class="space-y-4">
                <button id="pay-button" class="group w-full bg-emerald-600 hover:bg-emerald-700 text-white font-bold py-5 rounded-2xl shadow-[0_15px_30px_-5px_rgba(16,185,129,0.4)] transition-all duration-300 active:scale-[0.98] flex items-center justify-center gap-3">
                    <i class="fa-solid fa-credit-card group-hover:rotate-12 transition-transform"></i>
                    <span class="text-lg text-white">Bayar Sekarang</span>
                </button>
                
                <a href="/dashboard" class="w-full inline-flex items-center justify-center py-4 text-slate-400 hover:text-slate-600 font-semibold text-sm transition-colors uppercase tracking-widest">
                    Nanti Saja
                </a>
            </div>

            <div class="mt-6 pt-8 border-t border-slate-50 flex flex-col items-center">
                <p class="text-[9px] font-bold text-slate-300 uppercase tracking-widest mb-3">Secure Payment by</p>
                <img src="https://upload.wikimedia.org/wikipedia/commons/thumb/b/ba/Logo_Midtrans.png/1200px-Logo_Midtrans.png" class="h-4 opacity-30 grayscale" alt="Midtrans">
            </div>
        </div>
    </div>
</div>

<script src="https://app.sandbox.midtrans.com/snap/snap.js" data-client-key="{{ env('MIDTRANS_CLIENT_KEY') }}"></script>

<script>
    const btn = document.getElementById('pay-button');

    btn.onclick = function(e) {
        e.preventDefault();
        
        const originalContent = btn.innerHTML;
        btn.innerHTML = '<i class="fa-solid fa-circle-notch animate-spin"></i> Menghubungkan...';
        btn.disabled = true;

        snap.pay('{{ $snapToken }}', {
            onSuccess: function(result) {
                window.location.href = "/dashboard";
            },
            onPending: function(result) {
                alert("Menunggu pembayaran Anda.");
                resetBtn();
            },
            onError: function(result) {
                alert("Pembayaran gagal, silakan coba lagi.");
                resetBtn();
            },
            onClose: function() {
                resetBtn();
            }
        });

        function resetBtn() {
            btn.innerHTML = originalContent;
            btn.disabled = false;
        }
    };
</script>