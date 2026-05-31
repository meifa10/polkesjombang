<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Pembayaran Pasien</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" rel="stylesheet">
    <script src="https://app.sandbox.midtrans.com/snap/snap.js" data-client-key="{{ config('services.midtrans.client_key') }}"></script>
</head>

<body class="bg-slate-100 min-h-screen">

    {{-- LOGIKA PERHITUNGAN ULANG (AGAR TOTAL SELALU AKURAT) --}}
    @php
        $biayaDokterFixed = 10000;
        $biayaAdmin = $pembayaran->biaya_admin ?? 10000;
        $biayaObat = $pembayaran->total_obat ?? 0;
        $totalTagihanFix = $biayaDokterFixed + $biayaAdmin + $biayaObat;
    @endphp

    <div class="min-h-screen flex items-center justify-center p-6">
        <div class="w-full max-w-lg">
            <a href="/dashboard" class="inline-flex items-center gap-2 text-sm text-slate-500 hover:text-emerald-600 font-bold mb-5 transition-all">
                <i class="fa-solid fa-arrow-left"></i> Kembali ke Dashboard
            </a>

            <div class="bg-white rounded-[35px] overflow-hidden border border-slate-200 shadow-2xl">
                <div class="bg-gradient-to-r from-emerald-600 to-emerald-700 p-8 text-white relative overflow-hidden">
                    <div class="relative z-10">
                        <h1 class="text-3xl font-black">POLKES JOMBANG</h1>
                    </div>
                </div>

                <div class="p-8">
                    {{-- TOTAL TAGIHAN (MENGGUNAKAN VARIABEL $totalTagihanFix) --}}
                    <div class="bg-slate-50 rounded-3xl p-8 border border-slate-200 mb-6">
                        <p class="text-xs uppercase font-black text-slate-400 mb-3">Total Tagihan</p>
                        <h2 class="text-5xl font-black text-slate-900">
                            <span class="text-2xl text-emerald-600">Rp</span>
                            {{ number_format($totalTagihanFix, 0, ',', '.') }}
                        </h2>
                    </div>

                    {{-- TOMBOL BAYAR --}}
                    @if($pembayaran->status != 'lunas')
                        <button id="pay-button" class="w-full h-16 rounded-2xl bg-emerald-600 hover:bg-emerald-700 text-white font-black uppercase tracking-[0.2em] transition-all shadow-xl active:scale-95">
                            Bayar Sekarang
                        </button>
                    @else
                        <div class="text-center text-emerald-600 font-black text-xl">✓ Pembayaran Berhasil</div>
                    @endif
                </div>
            </div>
        </div>
    </div>

    <script>
        const payBtn = document.getElementById('pay-button');
        const snapToken = @json($snapToken);

        if (payBtn) {
            payBtn.addEventListener('click', function () {
                snap.pay(snapToken, {
                    onSuccess: function(result) { window.location.reload(); },
                    onPending: function(result) { window.location.href = "/dashboard"; },
                    onError: function(result) { alert("Pembayaran gagal."); }
                });
            });
        }
    </script>
</body>
</html>