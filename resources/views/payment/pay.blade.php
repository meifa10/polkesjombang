<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Pembayaran Pasien - POLKES JOMBANG</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" rel="stylesheet">
    <script src="https://app.sandbox.midtrans.com/snap/snap.js" data-client-key="{{ config('services.midtrans.client_key') }}"></script>
</head>

<body class="bg-slate-100 min-h-screen">

    <div class="min-h-screen flex items-center justify-center p-6 my-6">
        <div class="w-full max-w-xl">
            <a href="/dashboard" class="inline-flex items-center gap-2 text-sm text-slate-500 hover:text-emerald-600 font-bold mb-5 transition-all">
                <i class="fa-solid fa-arrow-left"></i> Kembali ke Dashboard
            </a>

            <div class="bg-white rounded-[35px] overflow-hidden border border-slate-200 shadow-2xl">
                <div class="bg-gradient-to-r from-emerald-600 to-emerald-700 p-8 text-white relative overflow-hidden">
                    <h1 class="text-3xl font-black">POLKES JOMBANG</h1>
                </div>

                <div class="p-8 space-y-6">
                    <div class="border border-slate-200 rounded-2xl overflow-hidden shadow-sm">
                        <table class="w-full text-sm">
                            <thead class="bg-slate-50 text-slate-500 font-bold border-b border-slate-200 text-xs uppercase">
                                <tr><th class="py-3 px-4">Deskripsi</th><th class="py-3 px-4 text-right">Subtotal</th></tr>
                            </thead>
                            <tbody class="divide-y divide-slate-100 text-slate-700 font-medium">
                                <tr><td class="py-3 px-4">Jasa Dokter</td><td class="py-3 px-4 text-right font-bold">Rp {{ number_format($tarifDokter, 0, ',', '.') }}</td></tr>
                                <tr><td class="py-3 px-4">Administrasi</td><td class="py-3 px-4 text-right font-bold">Rp {{ number_format($tarifAdmin, 0, ',', '.') }}</td></tr>
                                <tr><td class="py-3 px-4">Obat & Farmasi</td><td class="py-3 px-4 text-right font-bold">Rp {{ number_format($pembayaran->total_obat, 0, ',', '.') }}</td></tr>
                            </tbody>
                        </table>
                    </div>

                    <div class="bg-emerald-50 rounded-3xl p-6 border-2 border-emerald-500/20">
                        <div class="flex justify-between items-center">
                            <p class="text-xs font-black text-slate-500">TOTAL TAGIHAN</p>
                            <h2 class="text-3xl font-black text-emerald-600">Rp {{ number_format($totalFix, 0, ',', '.') }}</h2>
                        </div>
                    </div>

                    <button id="pay-button" class="w-full h-16 rounded-2xl bg-emerald-600 hover:bg-emerald-700 text-white font-black uppercase tracking-[0.2em] transition-all shadow-xl active:scale-95">
                        <i class="fa-solid fa-credit-card mr-2"></i> BAYAR SEKARANG
                    </button>
                </div>
            </div>
        </div>
    </div>

    <script>
        const payBtn = document.getElementById('pay-button');
        const snapToken = "{{ $result['snap_token'] ?? '' }}"; 
        payBtn.addEventListener('click', function () {
            snap.pay(snapToken, {
                onSuccess: function(result) { window.location.reload(); },
                onError: function(result) { alert("Pembayaran gagal."); }
            });
        });
    </script>
</body>
</html>