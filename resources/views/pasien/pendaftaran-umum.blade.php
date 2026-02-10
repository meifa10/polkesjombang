@extends('layout.app')

@section('content')
<style>
.umum-wrapper {
    max-width: 900px;
    margin: 60px auto;
    padding: 40px;
    background: #ffffff;
    border-radius: 22px;
    box-shadow: 0 10px 30px rgba(0,0,0,0.08);
}
.umum-title {
    text-align: center;
    font-size: 26px;
    font-weight: 600;
    margin-bottom: 35px;
    color: #1f2937;
}
.form-group {
    margin-bottom: 22px;
}
.form-group label {
    display: block;
    font-size: 14px;
    font-weight: 500;
    color: #374151;
    margin-bottom: 8px;
}
.form-control {
    width: 100%;
    padding: 14px 16px;
    font-size: 14px;
    border-radius: 10px;
    border: 1px solid #e5e7eb;
}
.form-control:focus {
    border-color: #4f7cff;
    box-shadow: 0 0 0 3px rgba(79,124,255,0.15);
    outline: none;
}
.action-group {
    display: flex;
    justify-content: center;
    gap: 18px;
    margin-top: 35px;
}
.btn-back {
    background: #f59e0b;
    color: white;
    padding: 12px 30px;
    border-radius: 10px;
    text-decoration: none;
}
.btn-submit {
    background: #4f7cff;
    color: white;
    padding: 12px 32px;
    border-radius: 10px;
    border: none;
    cursor: pointer;
}
.btn-submit:hover {
    background: #3b66e5;
}
</style>

<div class="umum-wrapper">
    <div class="umum-title">Pasien Umum & Non JKN</div>

    <form method="POST" action="{{ route('pendaftaran.umum.store') }}">
        @csrf

        {{-- NAMA PASIEN --}}
        <div class="form-group">
            <label>Nama Pasien</label>
            <input
                type="text"
                name="nama_pasien"
                class="form-control"
                placeholder="Masukkan nama lengkap pasien"
                required
            >
        </div>

        {{-- PILIH IDENTITAS --}}
        <div class="form-group">
            <label>Jenis Identitas</label>
            <select name="identitas" class="form-control" required>
                <option value="">Pilih</option>
                <option value="KTP">KTP</option>
                <option value="RM">Rekam Medik</option>
            </select>
        </div>

        {{-- NOMOR IDENTITAS --}}
        <div class="form-group">
            <label>Nomor Identitas</label>
            <input
                type="text"
                name="no_identitas"
                class="form-control"
                placeholder="Masukkan nomor identitas"
                required
            >
        </div>

        {{-- TANGGAL LAHIR --}}
        <div class="form-group">
            <label>Tanggal Lahir</label>
            <input
                type="date"
                name="tanggal_lahir"
                class="form-control"
                required
            >
        </div>

        {{-- PILIH POLI --}}
        <div class="form-group">
            <label>Poliklinik Tujuan</label>
            <select name="poli" class="form-control" required>
                <option value="">Pilih Poli</option>
                <option value="Poli Umum">Poli Umum</option>
                <option value="Poli Gigi">Poli Gigi</option>
                <option value="Poli KIA & KB">Poli KIA & KB</option>
            </select>
        </div>

        {{-- ACTION --}}
        <div class="action-group">
            <a href="{{ route('pendaftaran.poliklinik') }}" class="btn-back">
                KEMBALI
            </a>
            <button type="submit" class="btn-submit">
                DAFTAR
            </button>
        </div>
    </form>
</div>
@endsection
