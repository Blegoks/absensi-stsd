@extends('juruarah.dashboard')

@section('title', 'Buka Sesi Absensi')

@section('content')
<div class="bg-white p-6 rounded shadow-md max-w-md mx-auto">
    <h2 class="text-xl font-semibold mb-4">Buka Sesi Absensi</h2>

    @if(session('success'))
        <div class="bg-green-100 border border-green-400 text-green-700 p-3 rounded mb-4">
            {{ session('success') }}
        </div>
    @endif

    <form method="POST" action="{{ route('juruarah.sesi.buka') }}">
        @csrf
        <div class="mb-4">
            <label for="jenis_kegiatan" class="block text-sm font-medium text-gray-700">Pilih Kegiatan</label>
            <select name="jenis_kegiatan" id="jenis_kegiatan" class="mt-1 block w-full border rounded px-3 py-2" required>
                <option value="">-- Pilih Kegiatan --</option>
                @foreach($kegiatan as $item)
                    <option value="{{ $item }}">{{ $item }}</option>
                @endforeach
            </select>
        </div>
        <div class="flex justify-end">
            <button type="submit" class="bg-blue-600 hover:bg-blue-700 text-white font-semibold px-4 py-2 rounded">
                Buka Sesi
            </button>
        </div>
    </form>
</div>
@endsection
