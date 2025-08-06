@extends('admin.dashboard')

@section('title', 'Manajemen Juru Arah')

@section('content')
<div class="bg-white p-6 rounded shadow">
    <div class="flex justify-between items-center mb-4">
        <h2 class="text-xl font-semibold">Daftar Juru Arah</h2>
        <a href="{{ route('admin.create-user', ['role' => 'juruarah']) }}"
           class="bg-blue-600 text-white px-4 py-2 rounded hover:bg-blue-700">
            + Tambah Juru Arah
        </a>
    </div>

    <table class="table-auto w-full text-sm border">
        <thead class="bg-gray-100">
            <tr>
                <th class="px-3 py-2 border">No</th>
                <th class="px-3 py-2 border">Nama</th>
                <th class="px-3 py-2 border">Email</th>
                <th class="px-3 py-2 border">Aksi</th>
            </tr>
        </thead>
        <tbody>
            @forelse($juruarahList as $i => $juruarah)
            <tr>
                <td class="border px-3 py-2">{{ $i + 1 }}</td>
                <td class="border px-3 py-2">{{ $juruarah->name }}</td>
                <td class="border px-3 py-2">{{ $juruarah->email }}</td>
                <td class="border px-3 py-2 flex gap-2">
                    <a href="{{ route('admin.manage-juruarah.edit', $juruarah->id) }}"
                        class="bg-yellow-500 text-white px-3 py-1 rounded text-sm hover:bg-yellow-600">
                        Edit
                    </a>

                    <form method="POST" action="{{ route('admin.delete-user', $juruarah->id) }}"
                          onsubmit="return confirm('Yakin ingin menghapus juru arah ini?')">
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
                <td colspan="4" class="border px-3 py-2 text-center text-gray-500">Belum ada data juru arah.</td>
            </tr>
            @endforelse
        </tbody>
    </table>
</div>
@endsection
