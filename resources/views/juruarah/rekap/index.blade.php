@extends('juruarah.dashboard')

@section('title', 'Rekap Absensi')

@section('content')
<div class="bg-white p-6 rounded shadow">
    <h2 class="text-xl font-semibold mb-4">Rekap Absensi Anggota - {{ $juruarah->name }}</h2>
    <!-- Form Filter -->
    <form method="GET" class="mb-4 flex flex-wrap gap-2 items-end">
        <input type="text" name="search" value="{{ request('search') }}"
            placeholder="Cari nama anggota..."
            class="border p-2 rounded w-64" />

        <div>
            <label class="block text-sm">Tanggal Mulai</label>
            <input type="date" name="tanggal_mulai" value="{{ request('tanggal_mulai') }}"
                class="border p-2 rounded" />
        </div>

        <div>
            <label class="block text-sm">Tanggal Selesai</label>
            <input type="date" name="tanggal_selesai" value="{{ request('tanggal_selesai') }}"
                class="border p-2 rounded" />
        </div>

        <button type="submit" class="bg-blue-600 text-white px-3 py-1 rounded">
            Filter
        </button>

        <!-- Tombol Export Excel -->
        <a href="{{ route('juruarah.rekap.anggota.export', ['id' => $juruarah->id, 'search' => request('search'), 'tanggal_mulai' => request('tanggal_mulai'), 'tanggal_selesai' => request('tanggal_selesai')]) }}"
           class="bg-green-600 text-white px-3 py-1 rounded hover:bg-green-700">
            Export Excel
        </a>
    </form>

    <table class="table-auto w-full text-sm border">
        <thead class="bg-gray-100">
            <tr>
                <th class="px-3 py-2 border">No</th>
                <th class="px-3 py-2 border">Nama Anggota</th>
                <th class="px-3 py-2 border">Hadir</th>
                <th class="px-3 py-2 border">Izin</th>
                <th class="px-3 py-2 border">Tanpa Keterangan</th>
                <th class="px-3 py-2 border">Aksi</th>
            </tr>
        </thead>
        <tbody>
            @forelse ($anggotaList as $i => $anggota)
            <tr>
                <td class="border px-3 py-2">{{ $i + 1 }}</td>
                <td class="border px-3 py-2">{{ $anggota->nama }}</td>
                <td class="border px-3 py-2">
                    {{ $anggota->absensi->where('status', 'hadir')->count() }}
                </td>
                <td class="border px-3 py-2">
                    {{ $anggota->absensi->where('status', 'izin')->count() }}
                </td>
                <td class="border px-3 py-2">
                    {{ $anggota->absensi->where('status', 'alpha')->count() }}
                </td>
                <td class="border px-3 py-2">
                    <a href="{{ route('juruarah.rekap.absensi.detail', $anggota->id) }}"
                       class="bg-blue-600 text-white px-3 py-1 rounded text-sm">
                        Detail
                    </a>
                </td>
            </tr>
            @empty
            <tr>
                <td colspan="6" class="border px-3 py-2 text-center text-gray-500">Tidak ada data anggota.</td>
            </tr>
            @endforelse
        </tbody>
    </table>
</div>
@endsection
