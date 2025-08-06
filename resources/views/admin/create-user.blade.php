@extends('admin.dashboard')

@section('title', 'Tambah Pengguna')

@section('content')
<div class="bg-white p-6 rounded shadow max-w-lg mx-auto">
    <h2 class="text-xl font-semibold mb-4">Tambah Pengguna Baru</h2>
    <form action="{{ route('admin.store-user') }}" method="POST">
        @csrf

        {{-- Nama --}}
        <div class="mb-4">
            <label class="block text-sm font-medium text-gray-700">Nama</label>
            <input type="text" name="name" class="mt-1 block w-full border rounded px-3 py-2" required>
        </div>

        {{-- Email --}}
        <div class="mb-4">
            <label class="block text-sm font-medium text-gray-700">Email</label>
            <input type="email" name="email" class="mt-1 block w-full border rounded px-3 py-2" required>
        </div>

        {{-- Role --}}
        <div class="mb-4">
            <label class="block text-sm font-medium text-gray-700">Role</label>
            <select name="role" id="role-select" class="mt-1 block w-full border rounded px-3 py-2" required onchange="toggleTempekanField()">
                <option value="admin" {{ (old('role', $role ?? '') == 'admin') ? 'selected' : '' }}>Admin</option>
                <option value="juruarah" {{ (old('role', $role ?? '') == 'juruarah') ? 'selected' : '' }}>Juru Arah</option>
                <option value="anggota" {{ (old('role', $role ?? '') == 'anggota') ? 'selected' : '' }}>Anggota</option>
            </select>
        </div>

        {{-- Tempekan (Hanya untuk Anggota) --}}
        <div class="mb-4" id="tempekan-field" style="display: none;">
            <label class="block text-sm font-medium text-gray-700">Tempekan</label>
            <select name="tempekan" class="mt-1 block w-full border rounded px-3 py-2">
                <option value="">-- Pilih Tempekan --</option>
                @foreach($tempekans as $tempekan)
                    <option value="{{ $tempekan->id }}">{{ $tempekan->nama }}</option>
                @endforeach
            </select>
            <p class="text-xs text-gray-500 mt-1">Hanya diisi jika role adalah <strong>Anggota</strong>.</p>
        </div>

        {{-- Password (info) --}}
        <div class="mb-4">
            <label class="block text-sm font-medium text-gray-700">Password</label>
            <input type="text" name="password" class="mt-1 block w-full border rounded px-3 py-2 bg-gray-100 text-gray-400 cursor-not-allowed" value="(Akan dikirim otomatis ke email)" disabled>
            <p class="text-xs text-gray-500 mt-1">Password akan dibuat secara acak dan dikirim ke email pengguna.</p>
        </div>

        {{-- Tombol --}}
        <div class="flex items-center justify-between">
            <button type="submit" class="bg-blue-500 text-white px-4 py-2 rounded hover:bg-blue-600">Simpan</button>
            <a href="{{ url()->previous() }}" class="text-gray-600 hover:underline">Batal</a>
        </div>
    </form>

    {{-- Script Toggle --}}
    <script>
        function toggleTempekanField() {
            const role = document.getElementById('role-select').value;
            const tempekanField = document.getElementById('tempekan-field');
            if (role === 'anggota') {
                tempekanField.style.display = 'block';
            } else {
                tempekanField.style.display = 'none';
            }
        }

        document.addEventListener('DOMContentLoaded', function() {
            toggleTempekanField();
            document.getElementById('role-select').addEventListener('change', toggleTempekanField);
        });
    </script>
</div>
@endsection
