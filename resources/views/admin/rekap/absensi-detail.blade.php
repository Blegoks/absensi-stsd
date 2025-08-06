@extends('admin.dashboard')

@section('title', 'Detail Absensi - ' . $anggota->nama)

@section('content')
<div class="bg-white p-6 rounded shadow">
    <h2 class="text-xl font-semibold mb-4">Detail Absensi: {{ $anggota->nama }}</h2>

    <a href="{{ url()->previous() }}" class="text-blue-600 underline mb-3 inline-block">← Kembali</a>

    <form method="GET" class="mb-4">
        <div class="flex flex-wrap items-center gap-4">
            <div>
                <label class="block text-sm mb-1">Tanggal Mulai</label>
                <input type="date" name="start_date" value="{{ request('start_date') }}"
                    class="border rounded px-2 py-1 text-sm">
            </div>
            <div>
                <label class="block text-sm mb-1">Tanggal Selesai</label>
                <input type="date" name="end_date" value="{{ request('end_date') }}"
                    class="border rounded px-2 py-1 text-sm">
            </div>
            <div class="mt-5">
                <button type="submit"
                    class="bg-blue-600 text-white px-3 py-1 rounded text-sm hover:bg-blue-700">
                    Filter
                </button>
            </div>
        </div>
    </form>

    <a href="{{ route('admin.rekap.absensi.export', $anggota->id) }}"
        class="bg-green-600 text-white px-3 py-1 rounded text-sm hover:bg-green-700 mb-4 inline-block">
        ⇩ Export ke Excel
    </a>

    <table class="table-auto w-full text-sm border">
        <thead class="bg-gray-100">
            <tr>
                <th class="px-3 py-2 border">Tanggal</th>
                <th class="px-3 py-2 border">Status</th>
                <th class="px-3 py-2 border">Jenis Kegiatan</th>
            </tr>
        </thead>
        <tbody>
            @forelse ($absensi as $absen)
            <tr>
                <td class="border px-3 py-2">{{ \Carbon\Carbon::parse($absen->tanggal)->format('d-m-Y') }}</td>
                <td class="border px-3 py-2">{{ ucfirst($absen->status) }}</td>
                <td class="border px-3 py-2">{{ $absen->sesiAbsensi->jenis_kegiatan ?? '-' }}</td>
            </tr>
            @empty
            <tr>
                <td colspan="3" class="border px-3 py-2 text-center text-gray-500">Belum ada data absensi.</td>
            </tr>
            @endforelse
        </tbody>
    </table>
</div>
@endsection
