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

    {{-- LOGIKA PERHITUNGAN PAKSA: Mengabaikan data total_biaya di DB yang lama --}}
    @php
        $biayaDokterBaru = 10000;
        $biayaAdmin = $pembayaran->biaya_admin ?? 10000;
        $biayaObat = $pembayaran->total_obat ?? 0;
        $totalFix = $biayaDokterBaru + $biayaAdmin + $biayaObat;
    @endphp

    <div class="min-h-screen flex items-center justify-center p-6 my-6">
        <div class="w-full max-w-xl">

            {{-- BACK --}}
            <a href="/dashboard" class="inline-flex items-center gap-2 text-sm text-slate-500 hover:text-emerald-600 font-bold mb-5 transition-all">
                <i class="fa-solid fa-arrow-left"></i> Kembali ke Dashboard
            </a>

            {{-- CARD --}}
            <div class="bg-white rounded-[35px] overflow-hidden border border-slate-200 shadow-2xl">

                {{-- HEADER --}}
                <div class="bg-gradient-to-r from-emerald-600 to-emerald-700 p-8 text-white relative overflow-hidden">
                    <div class="absolute -right-10 -top-10 w-40 h-40 rounded-full bg-white/10"></div>
                    <div class="relative z-10">
                        <div class="flex items-center justify-between">
                            <div>
                                <p class="text-xs uppercase tracking-[0.3em] font-black text-emerald-100">Sistem Invoice & Pembayaran</p>
                                <h1 class="text-3xl font-black mt-2">POLKES JOMBANG</h1>
                            </div>
                            <div class="w-16 h-16 rounded-2xl bg-white/10 flex items-center justify-center text-3xl">📄</div>
                        </div>
                    </div>
                </div>

                {{-- BODY --}}
                <div class="p-8 space-y-6">
                    
                    {{-- STATUS & REF --}}
                    <div class="flex items-center justify-between border-b border-slate-100 pb-4">
                        <div>
                            <p class="text-xs uppercase font-black tracking-widest text-slate-400">Status</p>
                            <span class="inline-flex items-center gap-2 mt-1.5 px-3 py-1 rounded-full {{ $pembayaran->status == 'lunas' ? 'bg-emerald-100 text-emerald-700' : 'bg-amber-100 text-amber-700' }} text-xs font-black uppercase">
                                <span class="w-2 h-2 {{ $pembayaran->status == 'lunas' ? 'bg-emerald-500' : 'bg-amber-500' }} rounded-full"></span> {{ $pembayaran->status }}
                            </span>
                        </div>
                        <div class="text-right">
                            <p class="text-xs uppercase font-black tracking-widest text-slate-400">No. Ref</p>
                            <h3 class="text-sm font-black text-slate-800 mt-1.5 font-mono">{{ $pembayaran->payment_ref }}</h3>
                        </div>
                    </div>

                    {{-- INFORMASI PASIEN --}}
                    <div class="grid grid-cols-2 gap-4 bg-slate-50 rounded-2xl p-4 border border-slate-200 text-sm">
                        <div>
                            <p class="text-xs font-bold text-slate-400 uppercase tracking-wider">Nama Pasien</p>
                            <p class="font-extrabold text-slate-800 uppercase mt-0.5">{{ $pembayaran->pendaftaran->nama_pasien ?? '-' }}</p>
                        </div>
                        <div class="text-right">
                            <p class="text-xs font-bold text-slate-400 uppercase tracking-wider">Unit / Poli</p>
                            <p class="font-extrabold text-emerald-700 uppercase mt-0.5">{{ $pembayaran->pendaftaran->poli ?? '-' }}</p>
                        </div>
                    </div>

                    {{-- STRUK / RINCIAN TARIF --}}
                    <div>
                        <p class="text-xs uppercase font-black tracking-widest text-slate-400 mb-3">Rincian Komponen Struk</p>
                        <div class="border border-slate-200 rounded-2xl overflow-hidden shadow-sm">
                            <table class="w-full text-sm text-left">
                                <thead class="bg-slate-50 text-slate-500 font-bold border-b border-slate-200 text-xs uppercase">
                                    <tr>
                                        <th class="py-3 px-4">Deskripsi Layanan</th>
                                        <th class="py-3 px-4 text-right">Subtotal</th>
                                    </tr>
                                </thead>
                                <tbody class="divide-y divide-slate-100 text-slate-700 font-medium">
                                    {{-- Jasa Dokter --}}
                                    <tr>
                                        <td class="py-3 px-4">
                                            <p class="font-bold text-slate-800">Jasa Dokter & Konsultasi</p>
                                            <p class="text-xs text-slate-400">Pemeriksaan medis dasar klinis poli</p>
                                        </td>
                                        <td class="py-3 px-4 text-right font-bold text-slate-900">Rp {{ number_format($biayaDokterBaru, 0, ',', '.') }}</td>
                                    </tr>
                                    {{-- Administrasi --}}
                                    <tr>
                                        <td class="py-3 px-4">
                                            <p class="font-bold text-slate-800">Administrasi</p>
                                            <p class="text-xs text-slate-400">Pencatatan & berkas rekam medis</p>
                                        </td>
                                        <td class="py-3 px-4 text-right font-bold text-slate-900">Rp {{ number_format($biayaAdmin, 0, ',', '.') }}</td>
                                    </tr>
                                    {{-- Resep Farmasi --}}
                                    <tr>
                                        <td class="py-3 px-4">
                                            <p class="font-bold text-slate-800">Obat & Farmasi</p>
                                            <p class="text-xs text-slate-400">Akumulasi paket resep obat poli</p>
                                        </td>
                                        <td class="py-3 px-4 text-right font-bold text-slate-900">Rp {{ number_format($biayaObat, 0, ',', '.') }}</td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                    </div>

                    {{-- TOTAL DISPLAY --}}
                    <div class="bg-emerald-50/60 rounded-3xl p-6 border-2 border-emerald-500/20">
                        <div class="flex justify-between items-center">
                            <p class="text-xs uppercase tracking-[0.2em] font-black text-slate-500">Total Tagihan Bersih</p>
                            <h2 class="text-3xl font-black text-emerald-600 leading-none">
                                <span class="text-lg">Rp</span> {{ number_format($totalFix, 0, ',', '.') }}
                            </h2>
                        </div>
                    </div>

                    {{-- ACTION BUTTON --}}
                    <div class="pt-2">
                        @if($pembayaran->status != 'lunas')
                            <button id="pay-button" class="w-full h-16 rounded-2xl bg-emerald-600 hover:bg-emerald-700 text-white font-black uppercase tracking-[0.2em] transition-all shadow-xl shadow-emerald-200 active:scale-95">
                                <i class="fa-solid fa-credit-card mr-2"></i> Konfirmasi & Bayar Sekarang
                            </button>
                        @else
                            <a href="/pembayaran/struk/{{ $pembayaran->id }}" target="_blank" class="w-full h-16 rounded-2xl flex items-center justify-center bg-blue-600 hover:bg-blue-700 text-white font-black uppercase tracking-[0.1em] transition-all active:scale-95 shadow-xl shadow-blue-100">
                                <i class="fa-solid fa-print mr-2"></i> Cetak Struk / Invoice Resmi
                            </a>
                        @endif
                    </div>

                </div>
            </div>
        </div>
    </div>

    <script>
        const payBtn = document.getElementById('pay-button');
        const snapToken = @json($snapToken);
        if (payBtn) {
            payBtn.addEventListener('click', function () {
                payBtn.disabled = true;
                payBtn.innerHTML = `<i class="fa-solid fa-spinner fa-spin mr-2"></i> MEMPROSES TRANSAKSI...`;
                snap.pay(snapToken, {
                    onSuccess: function(result) { window.location.reload(); },
                    onPending: function(result) { window.location.href = "/dashboard"; },
                    onError: function(result) { alert("Pembayaran gagal."); payBtn.disabled = false; payBtn.innerHTML = '<i class="fa-solid fa-credit-card mr-2"></i> Konfirmasi & Bayar Sekarang'; }
                });
            });
        }
    </script>
</body>
</html>