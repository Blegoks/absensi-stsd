@extends('admin.dashboard')

@section('title', 'Profil Saya')

@section('content')
<div class="bg-white p-6 rounded shadow">
    <h2 class="text-xl font-semibold mb-4">Profil Pengguna</h2>

    <div class="space-y-4">
        <div>
            <label class="block text-sm font-medium text-gray-700">Nama</label>
            <p class="text-gray-900">{{ $user->name }}</p>
        </div>
        <div>
            <label class="block text-sm font-medium text-gray-700">Email</label>
            <p class="text-gray-900">{{ $user->email }}</p>
        </div>
        <div>
            <label class="block text-sm font-medium text-gray-700">Role</label>
            <p class="text-gray-900 capitalize">{{ $user->role }}</p>
        </div>
        <div>
            <label class="block text-sm font-medium text-gray-700">Tanggal Dibuat</label>
            <p class="text-gray-900">{{ $user->created_at->format('d M Y H:i') }}</p>
        </div>
    </div>
</div>
@endsection
