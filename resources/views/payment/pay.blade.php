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

    <a href="/dashboard" class="mb-6 flex items-center gap-2 text-slate-400 hover:text-emerald-600 transition">
        <i class="fa-solid fa-arrow-left"></i>
        Kembali ke Dashboard
    </a>

    <div class="max-w-md w-full bg-white rounded-3xl shadow-lg overflow-hidden">

        <div class="bg-emerald-600 p-8 text-center text-white">
            <h2 class="text-xl font-bold">Pembayaran Pasien</h2>
            <p class="text-xs mt-2">Ref: {{ $pembayaran->payment_ref }}</p>
        </div>

        <div class="p-8">

            <div class="text-center mb-6">
                <h1 class="text-3xl font-bold text-slate-800">
                    Rp {{ number_format($pembayaran->total_biaya, 0, ',', '.') }}
                </h1>
            </div>

            <div class="mb-6">
                <p class="text-sm text-gray-500">Nama</p>
                <p class="font-bold">{{ Auth::user()->name }}</p>
            </div>

            @if($pembayaran->status === 'lunas')
                <div class="bg-green-100 text-green-700 p-4 rounded text-center">
                    Pembayaran sudah lunas
                </div>
            @else
                <button id="pay-button" class="w-full bg-emerald-600 text-white py-3 rounded-lg font-bold">
                    Bayar Sekarang
                </button>
            @endif

        </div>
    </div>
</div>

{{-- MIDTRANS --}}
<script src="https://app.sandbox.midtrans.com/snap/snap.js"
        data-client-key="{{ env('MIDTRANS_CLIENT_KEY') }}"></script>

<script>
    const btn = document.getElementById('pay-button');

    if (btn) {
        btn.onclick = function(e) {
            e.preventDefault();

            const token = "{{ $snapToken }}";

            console.log("=== DEBUG MIDTRANS ===");
            console.log("TOKEN:", token);
            console.log("SNAP:", window.snap);

            // 🔥 VALIDASI WAJIB
            if (!window.snap) {
                alert("❌ Snap Midtrans tidak terload!");
                return;
            }

            if (!token || token.trim() === "") {
                alert("❌ Snap token kosong!");
                return;
            }

            const originalContent = btn.innerHTML;
            btn.innerHTML = "Menghubungkan...";
            btn.disabled = true;

            try {
                window.snap.pay(token, {

                    onSuccess: function(result) {
                        console.log("SUCCESS:", result);
                        alert("Pembayaran berhasil!");
                        window.location.reload();
                    },

                    onPending: function(result) {
                        console.log("PENDING:", result);
                        alert("Menunggu pembayaran...");
                        window.location.reload();
                    },

                    onError: function(result) {
                        console.error("ERROR:", result);
                        alert("Pembayaran gagal!");
                        resetBtn();
                    },

                    onClose: function() {
                        console.log("CLOSED");
                        resetBtn();
                    }

                });

            } catch (err) {
                console.error("SNAP ERROR:", err);
                alert("Snap gagal dipanggil!");
                resetBtn();
            }

            function resetBtn() {
                btn.innerHTML = "Bayar Sekarang";
                btn.disabled = false;
            }
        };
    }
</script>

</body>
</html>