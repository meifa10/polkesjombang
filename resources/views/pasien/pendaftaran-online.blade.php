@extends('layout.app')

@section('content')
<div class="container pendaftaran-dashboard">

    <h2>Pendaftaran Online</h2>
    <p>Selamat datang, {{ auth()->user()->name }}</p>

    <div class="menu-grid">

        <a href="#" class="menu-card">Pendaftaran Poliklinik</a>
        <a href="#" class="menu-card">Informasi Antrian</a>
        <a href="#" class="menu-card">Jadwal Dokter</a>
        <a href="#" class="menu-card">Ketersediaan Rawat Inap</a>
        <a href="#" class="menu-card">Histori Kunjungan</a>
        <a href="#" class="menu-card">Rekam Medis</a>
        <a href="#" class="menu-card">Antrian Obat</a>

    </div>

</div>
@endsection
