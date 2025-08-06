@extends('admin.dashboard')

@section('title', 'Edit Juru Arah')

@section('content')
<div class="bg-white p-6 rounded shadow max-w-xl mx-auto">
    <h2 class="text-xl font-semibold mb-4">Edit Data Juru Arah</h2>

    <form action="{{ route('admin.manage-juruarah.update', $user->id) }}" method="POST">
        @csrf
        @method('PUT')

        <div class="mb-4">
            <label class="block mb-1 font-semibold">Nama</label>
            <input type="text" name="name" value="{{ old('name', $user->name) }}"
                   class="w-full border rounded px-3 py-2" required>
        </div>

        <div class="mb-4">
            <label class="block mb-1 font-semibold">Email</label>
            <input type="email" name="email" value="{{ old('email', $user->email) }}"
                   class="w-full border rounded px-3 py-2" required>
        </div>

        <div class="flex justify-end">
            <a href="{{ route('admin.manage-juruarah') }}"
               class="bg-gray-500 text-white px-4 py-2 rounded mr-2 hover:bg-gray-600">
                Batal
            </a>
            <button type="submit"
                    class="bg-blue-600 text-white px-4 py-2 rounded hover:bg-blue-700">
                Simpan Perubahan
            </button>
        </div>
    </form>
</div>
@endsection
