@extends('layout.app') 

@section('content')
<style>
.fasilitas-section {
    padding: 80px 20px;
    background: #f8fafc;
}

.fasilitas-header {
    text-align: center;
    margin-bottom: 60px;
}

.fasilitas-header h1 {
    font-size: 36px;
    font-weight: 700;
    color: #0f172a;
}

.fasilitas-header p {
    color: #64748b;
    margin-top: 10px;
}

.fasilitas-grid {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(260px, 1fr));
    gap: 25px;
    max-width: 1200px;
    margin: auto;
}

.fasilitas-card {
    background: white;
    border-radius: 20px;
    padding: 30px;
    box-shadow: 0 15px 30px rgba(0,0,0,.06);
    transition: .3s;
    text-align: center;
}

.fasilitas-card:hover {
    transform: translateY(-6px);
    box-shadow: 0 25px 45px rgba(0,0,0,.08);
}

.fasilitas-card i {
    font-size: 42px;
    color: #16a34a;
    margin-bottom: 18px;
}

.fasilitas-card h3 {
    font-size: 18px;
    margin-bottom: 8px;
    color: #0f172a;
}

.fasilitas-card p {
    font-size: 14px;
    color: #64748b;
}
</style>

<section class="fasilitas-section">
    <div class="fasilitas-header">
        <h1>Fasilitas Polkes</h1>
        <p>Fasilitas penunjang pelayanan kesehatan Polkes 05.09.15 Jombang</p>
    </div>

    <div class="fasilitas-grid">

        {{-- FASILITAS LAMA (TIDAK DIUBAH) --}}
        <div class="fasilitas-card">
            <i class="fa-solid fa-stethoscope"></i>
            <h3>Poli Umum</h3>
            <p>Pemeriksaan kesehatan umum oleh dokter berpengalaman</p>
        </div>

        <div class="fasilitas-card">
            <i class="fa-solid fa-tooth"></i>
            <h3>Poli Gigi</h3>
            <p>Layanan kesehatan gigi dan mulut</p>
        </div>

        <div class="fasilitas-card">
            <i class="fa-solid fa-person-pregnant"></i>
            <h3>Poli KIA & KB</h3>
            <p>Layanan ibu, anak, dan keluarga berencana</p>
        </div>

        <div class="fasilitas-card">
            <i class="fa-solid fa-bed-pulse"></i>
            <h3>Ruang Tindakan</h3>
            <p>Ruang tindakan medis dengan standar kesehatan</p>
        </div>

        <div class="fasilitas-card">
            <i class="fa-solid fa-wheelchair"></i>
            <h3>Ruang Tunggu</h3>
            <p>Ruang tunggu nyaman & bersih untuk pasien</p>
        </div>

        {{-- FASILITAS BARU --}}
        <div class="fasilitas-card">
            <i class="fa-solid fa-user-shield"></i>
            <h3>Ruang Jaga Piket Polkes</h3>
            <p>Ruang petugas piket untuk pengawasan dan kesiapsiagaan layanan.</p>
        </div>

        <div class="fasilitas-card">
            <i class="fa-solid fa-square-parking"></i>
            <h3>Parkir Polkes</h3>
            <p>Area parkir kendaraan yang aman dan tertata.</p>
        </div>

        <div class="fasilitas-card">
            <i class="fa-solid fa-file-circle-check"></i>
            <h3>Ruang Akreditasi</h3>
            <p>Ruang pengelolaan dokumen dan administrasi akreditasi.</p>
        </div>

        <div class="fasilitas-card">
            <i class="fa-solid fa-baby"></i>
            <h3>Ruang Pojok ASI</h3>
            <p>Ruang khusus ibu menyusui yang nyaman dan privat.</p>
        </div>

        <div class="fasilitas-card">
            <i class="fa-solid fa-kitchen-set"></i>
            <h3>Dapur Polkes</h3>
            <p>Fasilitas dapur untuk kebutuhan operasional Polkes.</p>
        </div>

        <div class="fasilitas-card">
            <i class="fa-solid fa-restroom"></i>
            <h3>Kamar Mandi Pasien & Petugas</h3>
            <p>Kamar mandi terpisah untuk pasien dan petugas.</p>
        </div>

        <div class="fasilitas-card">
            <i class="fa-solid fa-clipboard-user"></i>
            <h3>Ruang Pendaftaran</h3>
            <p>Tempat pendaftaran dan pelayanan administrasi pasien.</p>
        </div>

        <div class="fasilitas-card">
            <i class="fa-solid fa-hand-holding-medical"></i>
            <h3>Ruang Dukkes</h3>
            <p>Ruang dukungan kesehatan bagi anggota dan keluarga.</p>
        </div>

        <div class="fasilitas-card">
            <i class="fa-solid fa-person-walking"></i>
            <h3>Ruang Fisioterapi</h3>
            <p>Pelayanan terapi untuk pemulihan fungsi gerak.</p>
        </div>

    </div>
</section>
@endsection
