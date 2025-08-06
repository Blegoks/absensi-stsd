@extends('admin.dashboard')

@section('title', 'Board Admin')

@section('content')
<div class="bg-white p-6 rounded shadow">
    <h2 class="text-xl font-semibold mb-4">Board Admin</h2>

    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-8">
        <a href="{{ route('admin.manage-juruarah') }}" class="block p-4 bg-blue-50 rounded shadow hover:shadow-md hover:bg-blue-100 transition duration-200">
            <div class="text-blue-700 font-semibold">Juru Arah</div>
            <div class="text-3xl font-bold text-blue-900 mt-2">{{ $totalJuruArah ?? '0' }}</div>
            <div class="text-sm text-gray-600 mt-1">Total juru arah</div>
        </a>

        <a href="{{ route('admin.manage-anggota') }}" class="block p-4 bg-green-50 rounded shadow hover:shadow-md hover:bg-green-100 transition duration-200">
            <div class="text-green-700 font-semibold">Anggota</div>
            <div class="text-3xl font-bold text-green-900 mt-2">{{ $totalAnggota ?? '0' }}</div>
            <div class="text-sm text-gray-600 mt-1">Total anggota</div>
        </a>

        <a href="{{ route('admin.attendance') }}" class="block p-4 bg-yellow-50 rounded shadow hover:shadow-md hover:bg-yellow-100 transition duration-200">
            <div class="text-yellow-700 font-semibold">Absensi Hari Ini</div>
            <div class="text-3xl font-bold text-yellow-900 mt-2">{{ $todayAbsensi ?? '0' }}</div>
            <div class="text-sm text-gray-600 mt-1">Jumlah absensi hari ini</div>
        </a>

        <a href="{{ route('admin.rekap.index') }}" class="block p-4 bg-purple-50 rounded shadow hover:shadow-md hover:bg-purple-100 transition duration-200">
            <div class="text-purple-700 font-semibold">Rekap Absensi</div>
            <div class="text-3xl font-bold text-purple-900 mt-2">{{ $totalAbsensi ?? '0' }}</div>
            <div class="text-sm text-gray-600 mt-1">Total absensi tercatat</div>
        </a>
    </div>
</div>
<div style="width: 100%; height: 300px;">
    <h2 class="text-xl font-semibold mb-4">Grafik Kehadiran per Tempekan (6 Bulan Terakhir)</h2>
    <canvas id="tempekanBarChart"></canvas>
</div>

@push('chart-script')
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
    const ctx = document.getElementById('tempekanBarChart').getContext('2d');

    const data = {
        labels: {!! json_encode($tempekanLabels) !!},
        datasets: [
            {
                label: 'Hadir',
                data: {!! json_encode($tempekanHadir) !!},
                backgroundColor: 'rgba(54, 162, 235, 0.7)',
                barThickness: 20
            },
            {
                label: 'Izin',
                data: {!! json_encode($tempekanIzin) !!},
                backgroundColor: 'rgba(255, 206, 86, 0.7)',
                barThickness: 20
            },
            {
                label: 'Tidak hadir',
                data: {!! json_encode($tempekanTidakhadir) !!},
                backgroundColor: 'rgba(255, 99, 132, 0.7)',
                barThickness: 20
            }
        ]
    };

    const config = {
        type: 'bar',
        data: data,
        options: {
            responsive: true,
            maintainAspectRatio: false,
            scales: {
                x: { stacked: false },
                y: { stacked: false, beginAtZero: true }
            }
        }
    };

    new Chart(ctx, config);
</script>
@endpush
@endsection
