@extends('layout.app')

@section('content')
<style>
.wrapper {
    max-width: 1000px;
    margin: 70px auto;
}
.title {
    font-size: 28px;
    font-weight: 600;
    margin-bottom: 35px;
}
.grid {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(420px, 1fr));
    gap: 25px;
}
.card {
    display: flex;
    align-items: center;
    gap: 25px;
    padding: 30px;
    border-radius: 20px;
    text-decoration: none;
    transition: .3s;
}
.card:hover {
    transform: translateY(-4px);
}
.jkn { background: #dff8f5; }
.umum { background: #e8eeff; }
.rekam { background: #ecfeff; }

.icon {
    width: 70px;
    height: 70px;
    background: white;
    border-radius: 18px;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 34px;
}
.text h4 {
    font-size: 18px;
    margin-bottom: 6px;
}
.text p {
    font-size: 14px;
    color: #555;
}

/* FORM TOKEN */
.token-box {
    background: #ffffff;
    border-radius: 20px;
    padding: 30px;
}
.token-box input {
    width: 100%;
    padding: 14px;
    border-radius: 12px;
    border: 1px solid #ccc;
    margin-bottom: 15px;
}
.token-box button {
    width: 100%;
    padding: 14px;
    background: #7c3aed;
    color: white;
    border-radius: 12px;
    border: none;
    font-weight: 600;
}
</style>

<div class="wrapper">
    <div class="title">Pendaftaran Pasien</div>

    <div class="grid">

        {{-- PASIEN JKN --}}
        <a href="{{ route('pendaftaran.jkn') }}" class="card jkn">
            <div class="icon">🩺</div>
            <div class="text">
                <h4>PASIEN JKN</h4>
                <p>BPJS / JKN dengan rujukan</p>
            </div>
        </a>

        {{-- PASIEN UMUM --}}
        <a href="{{ route('pendaftaran.umum') }}" class="card umum">
            <div class="icon">🏥</div>
            <div class="text">
                <h4>UMUM & NON JKN</h4>
                <p>Pasien umum atau non BPJS</p>
            </div>
        </a>

        {{-- REKAM MEDIS SAYA --}}
        <div class="card rekam">
            <div class="icon">📄</div>
            <div class="text" style="width:100%">
                <h4>REKAM MEDIS SAYA</h4>
                <p>Masukkan kode akses rekam medis</p>

                <div class="token-box">
                    <form action="{{ route('pasien.rekammedis') }}" method="GET">
                        <input 
                            type="text" 
                            name="token" 
                            placeholder="Contoh: RM-7F92A1C3" 
                            required
                        >
                        <button type="submit">
                            Lihat Rekam Medis Saya
                        </button>
                    </form>
                </div>

            </div>
        </div>

    </div>
</div>
@endsection
