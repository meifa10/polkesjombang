@extends('admin.layout')

@section('content')

<div class="topbar">
    <h2>Dashboard Admin</h2>
    <span>Selamat datang, {{ auth()->user()->name }}</span>
</div>

<div class="card">
    <h3>Grafik Kunjungan Pasien</h3>

    <div style="display:flex; gap:40px; margin-top:20px;">
        <canvas id="pieChart" width="250"></canvas>
        <canvas id="barChart" width="250"></canvas>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
new Chart(document.getElementById('pieChart'), {
    type: 'pie',
    data: {
        labels: ['Poli Umum', 'Gigi', 'KIA'],
        datasets: [{
            data: [20, 15, 15],
            backgroundColor: ['#28a745','#17a2b8','#ffc107']
        }]
    }
});

new Chart(document.getElementById('barChart'), {
    type: 'bar',
    data: {
        labels: ['Jan','Feb','Mar','Apr'],
        datasets: [{
            label: 'Kunjungan',
            data: [10, 15, 7, 20],
            backgroundColor: '#28a745'
        }]
    }
});
</script>

@endsection
