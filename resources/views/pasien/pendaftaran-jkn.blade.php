@extends('layout.app')

@section('content')
<style>
.form-box {
    max-width: 900px;
    margin: 60px auto;
    background: #ffffff;
    padding: 40px;
    border-radius: 20px;
    box-shadow: 0 10px 30px rgba(0,0,0,0.08);
}
.form-title {
    text-align: center;
    font-size: 26px;
    font-weight: 600;
    margin-bottom: 35px;
}
.form-group {
    margin-bottom: 22px;
}
label {
    font-size: 14px;
    margin-bottom: 8px;
    display: block;
    font-weight: 500;
}
input, select {
    width: 100%;
    padding: 14px;
    border-radius: 10px;
    border: 1px solid #e5e7eb;
    font-size: 14px;
}
input:focus, select:focus {
    outline: none;
    border-color: #4f7cff;
    box-shadow: 0 0 0 3px rgba(79,124,255,0.15);
}
.actions {
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
    font-size: 14px;
    font-weight: 500;
}
.btn-submit {
    background: #4f7cff;
    color: white;
    padding: 12px 32px;
    border-radius: 10px;
    border: none;
    font-size: 14px;
    font-weight: 500;
    cursor: pointer;
}
.btn-submit:hover {
    background: #3b66e5;
}
</style>

<div class="form-box">
    <div class="form-title">Pasien JKN</div>

    <form method="POST" action="{{ route('pendaftaran.jkn.store') }}">
        @csrf

        {{-- NAMA PASIEN --}}
        <div class="form-group">
            <label>Nama Lengkap</label>
            <input
                type="text"
                name="nama_pasien"
                placeholder="Masukkan nama lengkap"
                required
            >
        </div>

        {{-- NOMOR BPJS --}}
        <div class="form-group">
            <label>Nomor BPJS</label>
            <input
                type="text"
                name="no_identitas"
                placeholder="Masukkan nomor BPJS"
                required
            >
        </div>

        {{-- TANGGAL LAHIR --}}
        <div class="form-group">
            <label>Tanggal Lahir</label>
            <input
                type="date"
                name="tanggal_lahir"
                required
            >
        </div>

        {{-- POLI TUJUAN --}}
        <div class="form-group">
            <label>Poli Tujuan</label>
            <select name="poli" required>
                <option value="">Pilih Poli</option>
                <option value="Poli Umum">Poli Umum</option>
                <option value="Poli Gigi">Poli Gigi</option>
                <option value="Poli KIA & KB">Poli KIA & KB</option>
            </select>
        </div>

        {{-- ACTION --}}
        <div class="actions">
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
