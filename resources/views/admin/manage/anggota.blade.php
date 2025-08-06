@extends('admin.dashboard')

@section('title', 'Manajemen Anggota')

@section('content')
<div class="bg-white p-6 rounded shadow">
    <div class="flex justify-between items-center mb-4">
        <h2 class="text-xl font-semibold">Daftar Anggota</h2>
        <a href="{{ route('admin.create-user', ['role' => 'anggota']) }}"
           class="bg-blue-600 text-white px-4 py-2 rounded hover:bg-blue-700">
            + Tambah Anggota
        </a>
    </div>

    <table class="table-auto w-full text-sm border">
        <thead class="bg-gray-100">
            <tr>
                <th class="px-3 py-2 border">No</th>
                <th class="px-3 py-2 border">Nama</th>
                <th class="px-3 py-2 border">Email</th>
                <th class="px-3 py-2 border">Tempekan</th>
                <th class="px-3 py-2 border">Aksi</th>
            </tr>
        </thead>
        <tbody>
            @forelse($anggotaList as $i => $anggota)
            <tr>
                <td class="border px-3 py-2">{{ $i + 1 }}</td>
                <td class="border px-3 py-2">{{ $anggota->nama }}</td>
                <td class="border px-3 py-2">{{ $anggota->email }}</td>
                <td class="border px-3 py-2">{{ $anggota->tempekan->nama ?? '-' }}</td>
                <td class="border px-3 py-2">
                    <form method="POST" action="{{ route('admin.delete-user', $anggota->id) }}"
                        onsubmit="return confirm('Yakin ingin menghapus anggota ini?')">

                        @csrf
                        @method('DELETE')
                        <button class="bg-red-600 text-white px-3 py-1 rounded text-sm hover:bg-red-700">
                            Hapus
                        </button>
                    </form>
                </td>
            </tr>
            @empty
            <tr>
                <td colspan="5" class="border px-3 py-2 text-center text-gray-500">Belum ada data anggota.</td>
            </tr>
            @endforelse
        </tbody>
    </table>
</div>
@endsection
