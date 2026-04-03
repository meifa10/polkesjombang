@extends('layout.app')

@section('content')
<script src="https://cdn.tailwindcss.com"></script>
<link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap" rel="stylesheet">
<link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">

<style>
    body { 
        font-family: 'Plus Jakarta Sans', sans-serif; 
        background-color: #f1f5f9;
    }
    .glass-card {
        background: #ffffff;
        border: 1px solid #e2e8f0;
    }
    /* Custom style untuk dropdown agar panah tidak ganggu */
    select {
        appearance: none;
        -webkit-appearance: none;
    }
</style>

<div class="min-h-screen py-16 px-4 flex flex-col items-center justify-center bg-[radial-gradient(#cbd5e1_1px,transparent_1px)] [background-size:24px_24px]">
    
    <div class="text-center mb-10">
        <span class="px-5 py-2 bg-emerald-600 text-white text-[11px] font-extrabold uppercase tracking-[0.2em] rounded-lg shadow-lg shadow-emerald-200">
            Registrasi Baru
        </span>
        <h2 class="mt-6 text-4xl font-[800] text-black tracking-tighter">Pasien Umum & Non JKN</h2>
        <p class="mt-2 text-slate-600 font-semibold">Silakan isi formulir pendaftaran di bawah ini</p>
    </div>

    <div class="max-w-2xl w-full glass-card rounded-[3rem] shadow-[0_30px_60px_-15px_rgba(0,0,0,0.1)] overflow-hidden">
        <div class="p-8 sm:p-12">
            
            <form method="POST" action="{{ route('pendaftaran.umum.store') }}" class="space-y-6">
                @csrf

                <div class="grid grid-cols-1 md:grid-cols-2 gap-7">
                    
                    {{-- NAMA PASIEN --}}
                    <div class="md:col-span-2 group">
                        <label class="block text-xs font-black text-black mb-2 ml-1 uppercase tracking-widest">Nama Pasien</label>
                        <div class="relative">
                            <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none text-slate-500">
                                <i class="fa-solid fa-circle-user text-lg"></i>
                            </div>
                            <input type="text" name="nama_pasien" value="{{ Auth::user()->name }}" readonly
                                class="block w-full pl-12 pr-4 py-4 bg-slate-100 border-none rounded-2xl text-slate-800 font-bold focus:outline-none cursor-not-allowed">
                        </div>
                    </div>

                    {{-- JENIS IDENTITAS --}}
                    <div class="group">
                        <label class="block text-xs font-black text-black mb-2 ml-1 uppercase tracking-widest">Jenis Identitas</label>
                        <div class="relative">
                            <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none text-emerald-600 transition-colors">
                                <i class="fa-solid fa-id-card"></i>
                            </div>
                            <select name="identitas" required
                                class="block w-full pl-12 pr-10 py-4 bg-white border-2 border-slate-100 rounded-2xl text-black font-bold focus:border-emerald-500 focus:ring-4 focus:ring-emerald-500/10 transition-all outline-none">
                                <option value="">Pilih Identitas</option>
                                <option value="KTP">KTP</option>
                                <option value="RM">Rekam Medik</option>
                            </select>
                        </div>
                    </div>

                    {{-- NOMOR IDENTITAS --}}
                    <div class="group">
                        <label class="block text-xs font-black text-black mb-2 ml-1 uppercase tracking-widest">Nomor Identitas</label>
                        <div class="relative">
                            <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none text-emerald-600 transition-colors">
                                <i class="fa-solid fa-hashtag"></i>
                            </div>
                            <input type="text" name="no_identitas" placeholder="Ketik nomor..." required
                                class="block w-full pl-12 pr-4 py-4 bg-white border-2 border-slate-100 rounded-2xl text-black font-bold placeholder:text-slate-300 focus:border-emerald-500 focus:ring-4 focus:ring-emerald-500/10 transition-all outline-none">
                        </div>
                    </div>

                    {{-- TANGGAL LAHIR --}}
                    <div class="group">
                        <label class="block text-xs font-black text-black mb-2 ml-1 uppercase tracking-widest">Tanggal Lahir</label>
                        <div class="relative">
                            <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none text-emerald-600 transition-colors">
                                <i class="fa-solid fa-calendar-day"></i>
                            </div>
                            <input type="date" name="tanggal_lahir" required
                                class="block w-full pl-12 pr-4 py-4 bg-white border-2 border-slate-100 rounded-2xl text-black font-bold focus:border-emerald-500 focus:ring-4 focus:ring-emerald-500/10 transition-all outline-none">
                        </div>
                    </div>

                    {{-- POLI TUJUAN --}}
                    <div class="group">
                        <label class="block text-xs font-black text-black mb-2 ml-1 uppercase tracking-widest">Poliklinik Tujuan</label>
                        <div class="relative">
                            <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none text-emerald-600 transition-colors">
                                <i class="fa-solid fa-stethoscope"></i>
                            </div>
                            <select name="poli" required
                                class="block w-full pl-12 pr-10 py-4 bg-white border-2 border-slate-100 rounded-2xl text-black font-bold focus:border-emerald-500 focus:ring-4 focus:ring-emerald-500/10 transition-all outline-none">
                                <option value="">Cari Poli</option>
                                <option value="Poli Umum">Poli Umum</option>
                                <option value="Poli Gigi">Poli Gigi</option>
                                <option value="Poli KIA & KB">Poli KIA & KB</option>
                            </select>
                        </div>
                    </div>
                </div>

                {{-- ACTION BUTTONS --}}
                <div class="flex flex-col sm:flex-row items-center justify-center gap-8 mt-12 pt-8 border-t border-slate-100">
                    <a href="{{ route('dashboard') }}" 
                        class="text-xs font-[800] text-slate-400 hover:text-rose-600 transition-all tracking-[0.2em] uppercase flex items-center gap-2 group order-2 sm:order-1">
                        <i class="fa-solid fa-xmark group-hover:rotate-90 transition-transform"></i>
                        Batal
                    </a>
                    
                    <button type="submit" 
                        class="px-10 py-3 bg-emerald-600 hover:bg-emerald-700 text-white text-[13px] font-[800] rounded-xl shadow-lg shadow-emerald-200 transition-all transform active:scale-95 flex items-center gap-3 order-1 sm:order-2">
                        <span>DAFTAR SEKARANG</span>
                        <i class="fa-solid fa-paper-plane text-[10px]"></i>
                    </button>
                </div>
            </form>
        </div>
    </div>

    <div class="mt-12 flex flex-col items-center gap-3">
        <div class="h-1 w-12 bg-emerald-200 rounded-full mb-1"></div>
        
        <div class="flex items-center gap-3 px-6 py-2 bg-emerald-50/50 border border-emerald-100 rounded-2xl">
            <i class="fa-solid fa-shield-check text-emerald-500 text-xs animate-pulse"></i>
            <p class="text-[11px] font-extrabold text-emerald-700 uppercase tracking-[0.2em]">
                Data aman & dilindungi sistem <span class="text-emerald-900">Polkes Jombang</span>
            </p>
        </div>

    </div>
</div>
@endsection