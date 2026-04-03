@extends('layout.app')

@section('content')
<script src="https://cdn.tailwindcss.com"></script>
<link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap" rel="stylesheet">
<link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">

<style>
    body { 
        font-family: 'Plus Jakarta Sans', sans-serif; 
        background-color: #f8fafc;
    }
    .bento-card {
        transition: all 0.5s cubic-bezier(0.16, 1, 0.3, 1);
        cursor: pointer;
    }
    /* Background kartu Hijau */
    .card-premium-green {
        background: linear-gradient(135deg, #064e3b 0%, #065f46 100%);
        box-shadow: 0 20px 40px -10px rgba(6, 78, 59, 0.3);
    }
    /* Background kartu Putih */
    .card-premium-white {
        background: #ffffff;
        border: 2px solid #f1f5f9;
        box-shadow: 0 10px 30px -5px rgba(0, 0, 0, 0.02);
    }
    /* Efek melayang saat dipilih/hover */
    .bento-card:hover {
        transform: translateY(-12px) scale(1.02);
        border-color: #10b981; /* Garis hijau muncul saat hover */
    }
    .glass-icon {
        background: rgba(255, 255, 255, 0.1);
        backdrop-filter: blur(8px);
        border: 1px solid rgba(255, 255, 255, 0.2);
    }
    .mesh-gradient {
        position: absolute;
        top: 0; left: 0; right: 0; bottom: 0;
        background-image: 
            radial-gradient(at 0% 0%, rgba(16, 185, 129, 0.1) 0, transparent 50%), 
            radial-gradient(at 100% 100%, rgba(5, 150, 105, 0.05) 0, transparent 50%);
        pointer-events: none;
    }
</style>

<div class="min-h-screen py-24 px-6 bg-[#f8fafc]">
    <div class="max-w-4xl mx-auto">
        
        <div class="text-center mb-20 relative">
            <div class="inline-flex items-center gap-3 px-4 py-2 bg-emerald-600 rounded-2xl shadow-lg shadow-emerald-100 mb-8">
                <i class="fa-solid fa-layer-group text-white text-[10px]"></i>
                <span class="text-white text-[10px] font-black uppercase tracking-[0.3em]">Opsi Pendaftaran</span>
            </div>
            <h2 class="text-5xl font-[800] text-slate-900 tracking-tighter leading-none mb-6">
                Silakan Pilih <span class="text-emerald-700">Metode</span>
            </h2>
            <p class="text-slate-400 font-bold text-xs uppercase tracking-[0.4em]">Klik salah satu jalur layanan di bawah</p>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-2 gap-10">

            {{-- PILIHAN 1: JKN --}}
            <a href="{{ route('pendaftaran.jkn') }}" class="bento-card card-premium-white group p-12 rounded-[3.5rem] flex flex-col justify-between min-h-[420px] relative overflow-hidden">
                <div class="mesh-gradient opacity-0 group-hover:opacity-100 transition-opacity duration-700"></div>
                
                <div class="absolute top-8 right-8 w-10 h-10 bg-emerald-600 rounded-full flex items-center justify-center text-white scale-0 group-hover:scale-100 transition-transform duration-500 shadow-lg shadow-emerald-200">
                    <i class="fa-solid fa-check"></i>
                </div>

                <div class="relative z-10">
                    <div class="w-16 h-16 bg-slate-50 text-emerald-700 rounded-2xl flex items-center justify-center text-2xl mb-12 border border-slate-100 group-hover:bg-emerald-700 group-hover:text-white transition-all duration-500 shadow-sm">
                        <i class="fa-solid fa-id-card-clip"></i>
                    </div>
                    
                    <h4 class="text-3xl font-[900] text-slate-900 tracking-tighter mb-4">Pasien JKN</h4>
                    <p class="text-slate-500 font-bold leading-relaxed pr-6 text-sm">
                        Registrasi melalui jalur BPJS Kesehatan dengan rujukan FKTP yang masih aktif.
                    </p>
                </div>

                <div class="relative z-10 flex items-center gap-4">
                    <span class="px-6 py-2.5 bg-slate-900 text-white text-[10px] font-black tracking-[0.2em] rounded-xl group-hover:bg-emerald-600 transition-colors">PILIH JALUR</span>
                    <i class="fa-solid fa-arrow-right-long text-slate-300 group-hover:text-emerald-600 group-hover:translate-x-2 transition-all"></i>
                </div>
            </a>

            {{-- PILIHAN 2: UMUM --}}
            <a href="{{ route('pendaftaran.umum') }}" class="bento-card card-premium-green group p-12 rounded-[3.5rem] flex flex-col justify-between min-h-[420px] relative overflow-hidden">
                <div class="absolute -top-24 -left-24 w-64 h-64 bg-white/5 rounded-full blur-3xl group-hover:bg-white/10 transition-all duration-700"></div>
                
                <div class="absolute top-8 right-8 w-10 h-10 bg-white rounded-full flex items-center justify-center text-emerald-700 scale-0 group-hover:scale-100 transition-transform duration-500 shadow-xl">
                    <i class="fa-solid fa-check"></i>
                </div>

                <div class="relative z-10">
                    <div class="glass-icon w-16 h-16 rounded-2xl flex items-center justify-center text-2xl text-white mb-12 group-hover:rotate-6 transition-transform shadow-lg">
                        <i class="fa-solid fa-user-doctor"></i>
                    </div>
                    
                    <h4 class="text-3xl font-[900] text-white tracking-tighter mb-4">Umum / Non JKN</h4>
                    <p class="text-emerald-100/70 font-bold leading-relaxed pr-6 text-sm">
                        Layanan untuk pasien mandiri, asuransi swasta, atau tanpa penjamin BPJS.
                    </p>
                </div>

                <div class="relative z-10 flex items-center gap-4">
                    <span class="px-6 py-2.5 bg-white text-emerald-900 text-[10px] font-black tracking-[0.2em] rounded-xl shadow-lg group-hover:bg-emerald-950 group-hover:text-white transition-all">PILIH JALUR</span>
                    <i class="fa-solid fa-arrow-right-long text-white/40 group-hover:translate-x-2 transition-all"></i>
                </div>
            </a>

        </div>

        <div class="mt-24 flex flex-col items-center">
            <div class="flex items-center gap-6 px-10 py-5 bg-white rounded-3xl border border-slate-100 shadow-sm group hover:border-emerald-100 transition-all">
                <div class="flex -space-x-3">
                    <div class="w-8 h-8 rounded-full bg-emerald-600 flex items-center justify-center border-2 border-white text-white text-[10px]">
                        <i class="fa-solid fa-shield-check"></i>
                    </div>
                    <div class="w-8 h-8 rounded-full bg-slate-900 flex items-center justify-center border-2 border-white text-white text-[10px]">
                        <i class="fa-solid fa-lock"></i>
                    </div>
                </div>
                <p class="text-[10px] font-black text-slate-800 uppercase tracking-[0.3em]">
                    Data aman & <span class="text-emerald-700 px-1">terverifikasi</span> Polkes Jombang
                </p>
            </div>
        </div>

    </div>
</div>
@endsection