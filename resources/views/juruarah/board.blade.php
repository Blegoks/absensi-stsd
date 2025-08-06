@extends('juruarah.dashboard')

@section('title', 'Dashboard Juru Arah')

@section('content')
<div class="bg-white p-6 rounded shadow">
    <h2 class="text-xl font-semibold mb-4">Dashboard Juru Arah</h2>

    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6 mb-8">
        <!-- Anggota -->
        <a href="{{ route('juruarah.anggota.index') }}" class="block p-4 bg-indigo-50 rounded shadow hover:shadow-md hover:bg-indigo-100 transition duration-200">
            <div class="text-indigo-700 font-semibold">Anggota</div>
            <div class="text-3xl font-bold text-indigo-900 mt-2">{{ $jumlahAnggota ?? '0' }}</div>
            <div class="text-sm text-gray-600 mt-1">Jumlah anggota binaan</div>
        </a>

        <!-- Kehadiran -->
        <a href="{{ route('juruarah.monitoring') }}" class="block p-4 bg-green-50 rounded shadow hover:shadow-md hover:bg-green-100 transition duration-200">
            <div class="text-green-700 font-semibold">Monitoring Absensi</div>
            <div class="text-3xl font-bold text-green-900 mt-2">{{ $totalHadir ?? '0' }}</div>
            <div class="text-sm text-gray-600 mt-1">Kehadiran yang tercatat</div>
        </a>

        <!-- Rekap -->
        <a href="{{ route('juruarah.rekap.index') }}" class="block p-4 bg-yellow-50 rounded shadow hover:shadow-md hover:bg-yellow-100 transition duration-200">
            <div class="text-yellow-700 font-semibold">Rekap Absensi</div>
            <div class="text-3xl font-bold text-yellow-900 mt-2">{{ $totalAbsensi ?? '0' }}</div>
            <div class="text-sm text-gray-600 mt-1">Total data absensi</div>
        </a>
    </div>

    <!-- Bar Chart Absensi 1 Bulan -->
    <div class="mt-8">
        <h3 class="text-lg font-semibold mb-2">Rekap Absensi 1 Bulan Terakhir per Anggota</h3>
        <canvas id="absensiBarChart" height="120"></canvas>
    </div>
</div>

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
    const ctx = document.getElementById('absensiBarChart').getContext('2d');
    const absensiBarChart = new Chart(ctx, {
        type: 'bar',
        data: {
            labels: {!! json_encode($chartData['labels']) !!},
            datasets: [
                {
                    label: 'Hadir',
                    data: {!! json_encode($chartData['hadir']) !!},
                    backgroundColor: 'rgba(16, 185, 129, 0.7)',
                    borderColor: 'rgba(16, 185, 129, 1)',
                    borderWidth: 1
                },
                {
                    label: 'Izin',
                    data: {!! json_encode($chartData['izin']) !!},
                    backgroundColor: 'rgba(251, 191, 36, 0.7)',
                    borderColor: 'rgba(251, 191, 36, 1)',
                    borderWidth: 1
                },
                {
                    label: 'Tidak Hadir',
                    data: {!! json_encode($chartData['tidak_hadir']) !!},
                    backgroundColor: 'rgba(239, 68, 68, 0.7)',
                    borderColor: 'rgba(239, 68, 68, 1)',
                    borderWidth: 1
                }
            ]
        },
        options: {
            responsive: true,
            plugins: {
                legend: { display: true },
                title: { display: false }
            },
            scales: {
                x: {
                    stacked: false
                },
                y: {
                    beginAtZero: true,
                    precision: 0,
                    stacked: false
                }
            }
        }
    });
</script>
@endpush
@endsection
