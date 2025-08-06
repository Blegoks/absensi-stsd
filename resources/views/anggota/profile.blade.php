@extends('anggota.layout')

@section('title', 'Profil Saya')

@section('content')
<div class="bg-white p-6 rounded shadow max-w-md mx-auto">
    <h2 class="text-xl font-semibold mb-4">Profil Saya</h2>

    <div class="space-y-4 mb-6">
        <div>
            <label class="block text-sm font-medium text-gray-700">Nama</label>
            <p class="text-gray-900">{{ $user->name }}</p>
        </div>
        <div>
            <label class="block text-sm font-medium text-gray-700">Email</label>
            <p class="text-gray-900">{{ $user->email }}</p>
        </div>
    </div>

    <h3 class="text-lg font-semibold mb-2">Ganti Password</h3>
    @if(session('status'))
    <div class="mb-4 px-4 py-2 bg-green-100 text-green-800 rounded">
        {{ session('status') }}
    </div>
@endif
    <form action="{{ route('anggota.profile.reset-password') }}" method="POST">
        @csrf
        <div class="mb-4">
            <label class="block text-sm font-medium mb-1" for="current_password">Password Saat Ini</label>
            <input type="password" name="current_password" id="current_password"
                class="w-full border rounded p-2" required>
            @error('current_password')
                <p class="text-red-600 text-sm">{{ $message }}</p>
            @enderror
        </div>
        <div class="mb-4">
            <label class="block text-sm font-medium mb-1" for="new_password">Password Baru</label>
            <input type="password" name="new_password" id="new_password"
                class="w-full border rounded p-2" required>
            @error('new_password')
                <p class="text-red-600 text-sm">{{ $message }}</p>
            @enderror
        </div>
        <div class="mb-4">
            <label class="block text-sm font-medium mb-1" for="new_password_confirmation">Konfirmasi Password Baru</label>
            <input type="password" name="new_password_confirmation" id="new_password_confirmation"
                class="w-full border rounded p-2" required>
        </div>
        <button type="submit" class="bg-blue-600 text-white px-4 py-2 rounded">Simpan Password</button>
    </form>
</div>
@endsection
