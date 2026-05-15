<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Pembayaran Pasien</title>

    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" rel="stylesheet">

    {{-- MIDTRANS SNAP --}}
    <script src="https://app.sandbox.midtrans.com/snap/snap.js" data-client-key="{{ config('services.midtrans.client_key') }}"></script>
</head>

<body class="bg-slate-100 min-h-screen">

    <div class="min-h-screen flex items-center justify-center p-6">
        <div class="w-full max-w-lg">

            {{-- BACK --}}
            <a href="/dashboard" class="inline-flex items-center gap-2 text-sm text-slate-500 hover:text-emerald-600 font-bold mb-5 transition-all">
                <i class="fa-solid fa-arrow-left"></i>
                Kembali ke Dashboard
            </a>

            {{-- CARD --}}
            <div class="bg-white rounded-[35px] overflow-hidden border border-slate-200 shadow-2xl">

                {{-- HEADER --}}
                <div class="bg-gradient-to-r from-emerald-600 to-emerald-700 p-8 text-white relative overflow-hidden">
                    <div class="absolute -right-10 -top-10 w-40 h-40 rounded-full bg-white/10"></div>
                    <div class="relative z-10">
                        <div class="flex items-center justify-between">
                            <div>
                                <p class="text-xs uppercase tracking-[0.3em] font-black text-emerald-100">
                                    Sistem Pembayaran
                                </p>
                                <h1 class="text-3xl font-black mt-2">
                                    POLKES JOMBANG
                                </h1>
                            </div>
                            <div class="w-16 h-16 rounded-2xl bg-white/10 flex items-center justify-center text-3xl">
                                💳
                            </div>
                        </div>
                    </div>
                </div>

                {{-- BODY --}}
                <div class="p-8">

                    {{-- STATUS --}}
                    <div class="flex items-center justify-between mb-6">
                        <div>
                            <p class="text-xs uppercase font-black tracking-widest text-slate-400">
                                Status Pembayaran
                            </p>

                            @if($pembayaran && $pembayaran->status != 'lunas')
                                <span class="inline-flex items-center gap-2 mt-2 px-4 py-2 rounded-full bg-emerald-100 text-emerald-700 text-xs font-black uppercase">
                                    <span class="w-2 h-2 bg-emerald-500 rounded-full"></span>
                                    Lunas
                                </span>
                            @elseif($pembayaran->status == 'pending')
                                <span class="inline-flex items-center gap-2 mt-2 px-4 py-2 rounded-full bg-amber-100 text-amber-700 text-xs font-black uppercase">
                                    <span class="w-2 h-2 bg-amber-500 rounded-full animate-pulse"></span>
                                    Pending
                                </span>
                            @else
                                <span class="inline-flex items-center gap-2 mt-2 px-4 py-2 rounded-full bg-red-100 text-red-700 text-xs font-black uppercase">
                                    <span class="w-2 h-2 bg-red-500 rounded-full"></span>
                                    Gagal
                                </span>
                            @endif
                        </div>

                        <div class="text-right">
                            <p class="text-xs uppercase font-black tracking-widest text-slate-400">
                                Invoice
                            </p>
                            <h3 class="text-sm font-black text-slate-800 mt-2">
                                {{ $pembayaran->payment_ref }}
                            </h3>
                        </div>
                    </div>

                    {{-- TOTAL --}}
                    <div class="bg-slate-50 rounded-3xl p-8 border border-slate-200 mb-6">
                        <p class="text-xs uppercase tracking-[0.3em] font-black text-slate-400 mb-3">
                            Total Tagihan
                        </p>
                        <h2 class="text-5xl font-black text-slate-900 leading-none">
                            <span class="text-2xl text-emerald-600">Rp</span>
                            {{ number_format($pembayaran->total_biaya,0,',','.') }}
                        </h2>
                    </div>

                    {{-- DETAIL --}}
                    <div class="space-y-4 mb-8">
                        <div class="flex justify-between items-center">
                            <span class="text-slate-500 font-medium">Nama Pasien</span>
                            <span class="font-black text-slate-800 uppercase">
                                {{ $pembayaran->pendaftaran->nama_pasien ?? '-' }}
                            </span>
                        </div>
                        <div class="flex justify-between items-center">
                            <span class="text-slate-500 font-medium">Poli</span>
                            <span class="font-black text-slate-800 uppercase">
                                {{ $pembayaran->pendaftaran->poli ?? '-' }}
                            </span>
                        </div>
                        <div class="flex justify-between items-center">
                            <span class="text-slate-500 font-medium">Metode</span>
                            <span class="font-black text-slate-800 uppercase">
                                MIDTRANS
                            </span>
                        </div>
                    </div>

                    {{-- BUTTON & CETAK STRUK --}}
                    @if($pembayaran->status != 'lunas')
                        <button id="pay-button" class="w-full h-16 rounded-2xl bg-emerald-600 hover:bg-emerald-700 text-white font-black uppercase tracking-[0.2em] transition-all shadow-xl shadow-emerald-200 active:scale-95">
                            <i class="fa-solid fa-lock mr-2"></i>
                            Bayar Sekarang
                        </button>
                    @else
                        <div class="bg-emerald-50 border border-emerald-200 rounded-3xl p-8 text-center">
                            <div class="w-20 h-20 mx-auto rounded-full bg-emerald-600 flex items-center justify-center text-white text-4xl mb-5">
                                ✓
                            </div>
                            <h2 class="text-2xl font-black text-emerald-700">
                                Pembayaran Berhasil
                            </h2>
                            <p class="text-slate-500 mt-2 mb-6">
                                Transaksi telah berhasil diverifikasi sistem.
                            </p>
                            
                            {{-- TOMBOL CETAK STRUK --}}
                            <a href="/pembayaran/struk/{{ $pembayaran->id }}" target="_blank" class="inline-flex items-center justify-center w-full h-14 rounded-xl bg-white border-2 border-emerald-600 text-emerald-600 hover:bg-emerald-600 hover:text-white font-black uppercase tracking-[0.1em] transition-all shadow-sm active:scale-95">
                                <i class="fa-solid fa-print mr-2"></i>
                                Cetak Struk
                            </a>
                        </div>
                    @endif

                    {{-- FOOTER --}}
                    <div class="mt-10 pt-6 border-t border-slate-100">
                        <div class="flex justify-center items-center gap-5 opacity-50">
                            <i class="fa-brands fa-cc-visa text-3xl text-slate-400"></i>
                            <i class="fa-brands fa-cc-mastercard text-3xl text-slate-400"></i>
                            <i class="fa-solid fa-qrcode text-3xl text-slate-400"></i>
                            <i class="fa-solid fa-building-columns text-3xl text-slate-400"></i>
                        </div>
                        <p class="text-center text-[10px] uppercase tracking-widest font-bold text-slate-300 mt-4">
                            Secure Payment by Midtrans
                        </p>
                    </div>

                </div>
            </div>
        </div>
    </div>

    {{-- MIDTRANS SCRIPT --}}
    <script>
        const payBtn = document.getElementById('pay-button');
        const snapToken = @json($snapToken);

        if (payBtn) {
            payBtn.addEventListener('click', function () {
                payBtn.disabled = true;
                payBtn.innerHTML = `
                    <i class="fa-solid fa-spinner fa-spin mr-2"></i>
                    MEMPROSES...
                `;

                snap.pay(snapToken, {
                    onSuccess: function(result) {
                        console.log(result);
                        // Me-refresh halaman agar tombol Cetak Struk muncul
                        window.location.reload(); 
                    },
                    onPending: function(result) {
                        console.log(result);
                        // Jika pending (misal baru dapat kode VA), arahkan ke dashboard
                        window.location.href = "/dashboard";
                    },
                    onError: function(result) {
                        console.log(result);
                        alert("Pembayaran gagal atau dibatalkan.");
                        resetButton();
                    },
                    onClose: function() {
                        resetButton();
                    }
                });
            });
        }

        function resetButton() {
            payBtn.disabled = false;
            payBtn.innerHTML = `
                <i class="fa-solid fa-lock mr-2"></i>
                Bayar Sekarang
            `;
        }
    </script>
</body>
</html>