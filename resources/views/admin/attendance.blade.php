@extends('admin.dashboard')

@section('title', 'Absensi')

@section('content')
<div class="bg-white p-6 rounded shadow">
    <h2 class="text-xl font-semibold mb-4">Statistik Kehadiran Hari Ini ({{ \Carbon\Carbon::today()->format('d M Y') }})</h2>

    <div class="grid grid-cols-1 md:grid-cols-3 gap-4 mb-6">
        <div class="bg-green-100 p-4 rounded shadow text-center">
            <h3 class="text-lg font-semibold text-green-800">Hadir</h3>
            <p class="text-3xl font-bold text-green-700">{{ $totalHadir }}</p>
        </div>
        <div class="bg-yellow-100 p-4 rounded shadow text-center">
            <h3 class="text-lg font-semibold text-yellow-800">Izin</h3>
            <p class="text-3xl font-bold text-yellow-700">{{ $totalIzin }}</p>
        </div>
        <div class="bg-red-100 p-4 rounded shadow text-center">
            <h3 class="text-lg font-semibold text-red-800">Tanpa Keterangan</h3>
            <p class="text-3xl font-bold text-red-700">{{ $totalTidakhadir }}</p>
        </div>
    </div>

    <h3 class="font-semibold text-lg mb-2">Absensi per Sesi</h3>
    <div class="overflow-auto">
        <table class="w-full table-auto text-sm border">
            <thead class="bg-gray-100">
    <tr>
        <th class="px-4 py-2">Sesi</th>
        <th class="px-4 py-2">Juru Arah</th>
        <th class="px-4 py-2">Jenis Kegiatan</th>
        <th class="px-4 py-2">Tanggal</th>
        <th class="px-4 py-2">Jumlah Hadir</th>
    </tr>
</thead>
<tbody>
    @forelse ($absenPerSesi as $sesi)
    <tr>
        <td class="border px-4 py-2">#{{ $sesi->id }}</td>
        <td class="border px-4 py-2">{{ $sesi->juruArah->nama ?? '-' }}</td>
        <td class="border px-4 py-2">{{ $sesi->jenis_kegiatan ?? '-' }}</td>
        <td class="border px-4 py-2">{{ \Carbon\Carbon::parse($sesi->dibuka_pada)->translatedFormat('d F Y H:i') }}</td>
        <td class="border px-4 py-2">{{ $sesi->absensi->count() }}</td>
    </tr>
    @empty
    <tr><td colspan="5" class="text-center py-4 text-gray-500">Belum ada sesi absensi.</td></tr>
    @endforelse
</tbody>
        </table>
    </div>
</div>
@endsection
