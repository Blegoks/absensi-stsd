@extends('admin.dashboard')

@section('title', 'Rekap Absensi - Juruarah')

@section('content')
<div class="bg-white p-6 rounded shadow">
    <h2 class="text-xl font-semibold mb-4">Daftar Juruarah</h2>

    <table class="table-auto w-full text-sm border">
        <thead class="bg-gray-100">
            <tr>
                <th class="px-3 py-2 border">No</th>
                <th class="px-3 py-2 border">Nama Juruarah</th>
                <th class="px-3 py-2 border">Aksi</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($juruarahList as $i => $juru)
            <tr>
                <td class="border px-3 py-2">{{ $i + 1 }}</td>
                <td class="border px-3 py-2">{{ $juru->name }}</td>
                <td class="border px-3 py-2">
                    <a href="{{ route('admin.rekap.anggota', $juru->id) }}"
                       class="bg-blue-500 text-white px-3 py-1 rounded text-sm">Daftar Anggota</a>
                </td>
            </tr>
            @endforeach
        </tbody>
    </table>
</div>
@endsection
