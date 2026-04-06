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
        data-client-key="{{ env('MIDTRANS_CLIENT_KEY') }}">
    </script>
</head>

<body class="bg-slate-50">

<div class="min-h-screen flex flex-col items-center justify-center p-6">

    <a href="/dashboard" class="mb-6 flex items-center gap-2 text-slate-400 hover:text-emerald-600 font-medium text-sm">
        <i class="fa-solid fa-arrow-left"></i>
        Kembali ke Dashboard
    </a>

    <div class="max-w-md w-full bg-white rounded-3xl shadow-lg overflow-hidden">

        <div class="bg-emerald-600 p-8 text-center text-white">
            <h2 class="text-xl font-bold">Pembayaran Pasien</h2>
            <p class="text-xs mt-2">
                Ref: {{ $pembayaran->payment_ref ?? '-' }}
            </p>
        </div>

        <div class="p-8">

            <div class="text-center mb-6">
                <p class="text-gray-400 text-xs">TOTAL TAGIHAN</p>
                <h1 class="text-4xl font-bold text-emerald-600">
                    Rp {{ number_format($pembayaran->total_biaya, 0, ',', '.') }}
                </h1>
            </div>

            <div class="mb-6 text-sm">
                <p><b>Nama:</b> {{ $pembayaran->pendaftaran->nama_pasien ?? Auth::user()->name }}</p>
                <p><b>Status:</b> {{ $pembayaran->status }}</p>
            </div>

            @if($pembayaran->status !== 'lunas')
                <button id="pay-button"
                    class="w-full bg-emerald-600 text-white py-3 rounded-xl font-bold">
                    Bayar Sekarang
                </button>
            @else
                <div class="text-center text-emerald-600 font-bold">
                    ✅ Pembayaran Lunas
                </div>
            @endif

        </div>
    </div>
</div>

{{-- ============================= --}}
{{-- 🔥 SCRIPT FIX UTAMA --}}
{{-- ============================= --}}
<script>
    // ✅ GUNAKAN JSON ENCODE (WAJIB)
    const snapToken = @json($snapToken);

    console.log("SNAP TOKEN:", snapToken);

    const btn = document.getElementById('pay-button');

    if (btn) {
        btn.addEventListener('click', function(e) {
            e.preventDefault();

            // ❌ CEK TOKEN
            if (!snapToken || snapToken === null || snapToken === "") {
                alert("Error: Token pembayaran tidak ditemukan. Silakan refresh halaman.");
                console.error("SNAP TOKEN KOSONG!");
                return;
            }

            // ❌ CEK SNAP LOADED
            if (typeof window.snap === "undefined") {
                alert("Snap.js belum termuat. Coba refresh.");
                console.error("Snap.js tidak ditemukan");
                return;
            }

            btn.innerHTML = "Menghubungkan...";
            btn.disabled = true;

            window.snap.pay(snapToken, {
                onSuccess: function(result) {
                    alert("Pembayaran berhasil!");
                    console.log(result);
                    location.reload();
                },
                onPending: function(result) {
                    alert("Menunggu pembayaran...");
                    console.log(result);
                    location.reload();
                },
                onError: function(result) {
                    alert("Pembayaran gagal!");
                    console.log(result);
                    resetBtn();
                },
                onClose: function() {
                    resetBtn();
                }
            });

            function resetBtn() {
                btn.innerHTML = "Bayar Sekarang";
                btn.disabled = false;
            }
        });
    }
</script>

</body>
</html>