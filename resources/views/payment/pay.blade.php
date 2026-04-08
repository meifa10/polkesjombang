<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Pembayaran - Polkes Jombang</title>

    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">

    {{-- MIDTRANS SNAP --}}
    <script 
        src="https://app.sandbox.midtrans.com/snap/snap.js"
        data-client-key="{{ config('services.midtrans.client_key') }}">
    </script>
</head>

<body class="bg-slate-50">

<div class="min-h-screen flex flex-col items-center justify-center p-6">

    <a href="/dashboard" class="mb-6 flex items-center gap-2 text-slate-400 hover:text-emerald-600 font-medium text-sm transition-colors">
        <i class="fa-solid fa-arrow-left"></i>
        Kembali ke Dashboard
    </a>

    <div class="max-w-md w-full bg-white rounded-3xl shadow-xl overflow-hidden border border-gray-100">

        <div class="bg-emerald-600 p-8 text-center text-white">
            <h2 class="text-xl font-bold uppercase tracking-wider">Pembayaran Pasien</h2>
            <p class="text-xs mt-2 opacity-80 font-mono">
                Ref: {{ $pembayaran->payment_ref ?? '-' }}
            </p>
        </div>

        <div class="p-8">

            <div class="text-center mb-8">
                <p class="text-gray-400 text-[10px] font-bold uppercase tracking-widest mb-1">TOTAL TAGIHAN</p>
                <h1 class="text-4xl font-extrabold text-emerald-600">
                    {{-- 
                        🔥 FIX UTAMA: 
                        Kita bersihkan titik/desimal sebelum diformat ulang, 
                        agar tampilannya tidak jadi "Rp 50" kalau di DB ada titiknya.
                    --}}
                    Rp {{ number_format((int) str_replace(['.', ','], '', $pembayaran->total_biaya), 0, ',', '.') }}
                </h1>
            </div>

            <div class="mb-8 p-4 bg-gray-50 rounded-2xl space-y-3 text-sm border border-gray-100">
                <div class="flex justify-between border-b border-gray-200 pb-2">
                    <span class="text-gray-500">Nama Pasien</span>
                    <span class="font-bold text-gray-800">{{ $pembayaran->pendaftaran->nama_pasien ?? Auth::user()->name }}</span>
                </div>
                <div class="flex justify-between">
                    <span class="text-gray-500">Status</span>
                    <span class="px-2 py-0.5 rounded text-[10px] font-bold uppercase {{ $pembayaran->status === 'lunas' ? 'bg-green-100 text-green-700' : 'bg-red-100 text-red-700' }}">
                        {{ str_replace('_', ' ', $pembayaran->status) }}
                    </span>
                </div>
            </div>

            @if($pembayaran->status !== 'lunas')
                <button id="pay-button"
                    class="w-full bg-emerald-600 hover:bg-emerald-700 active:scale-95 text-white py-4 rounded-2xl font-bold shadow-lg shadow-emerald-200 transition-all flex justify-center items-center gap-2">
                    <i class="fa-solid fa-shield-check"></i>
                    Bayar Sekarang
                </button>
            @else
                <div class="flex flex-col items-center justify-center p-4 bg-emerald-50 rounded-2xl border border-emerald-200 text-emerald-700">
                    <i class="fa-solid fa-circle-check text-3xl mb-2"></i>
                    <span class="font-bold">Pembayaran Lunas</span>
                    <p class="text-[10px] opacity-70">Terima kasih, data sudah tersinkronisasi.</p>
                </div>
            @endif

            <div class="mt-6 flex justify-center items-center gap-4 opacity-30 grayscale">
                <img src="https://upload.wikimedia.org/wikipedia/commons/thumb/a/a2/Logo_BCA.svg/2560px-Logo_BCA.svg.png" class="h-4">
                <img src="https://upload.wikimedia.org/wikipedia/commons/thumb/5/5c/Bank_Central_Asia.svg/1200px-Bank_Central_Asia.svg.png" class="h-4">
            </div>

        </div>
    </div>
</div>

{{-- ============================= --}}
{{-- 🔥 SCRIPT FIX UTAMA --}}
{{-- ============================= --}}
<script>
    // ✅ GUNAKAN JSON ENCODE (SUDAH BENAR)
    const snapToken = @json($snapToken);

    const btn = document.getElementById('pay-button');

    if (btn) {
        btn.addEventListener('click', function(e) {
            e.preventDefault();

            // ❌ VALIDASI TOKEN
            if (!snapToken || snapToken === "" || snapToken === null) {
                alert("Token pembayaran tidak ditemukan. Mohon hubungi admin atau refresh halaman.");
                return;
            }

            // ❌ VALIDASI SNAP LOADED
            if (typeof window.snap === "undefined") {
                alert("Sistem pembayaran (Snap.js) gagal dimuat. Pastikan koneksi internet stabil.");
                return;
            }

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
                    alert("Pembayaran gagal diproses. Silakan coba lagi.");
                    resetBtn();
                },
                onClose: function() {
                    console.log("CLOSE: Customer closed the popup without finishing the payment");
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