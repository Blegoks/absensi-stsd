@extends('juruarah.dashboard')

@section('title', 'Tutup Sesi Absensi')

@section('content')
    <div class="bg-white shadow-md rounded p-6 max-w-xl mx-auto">
        @if(session('success'))
            <div class="bg-green-100 text-green-800 p-3 rounded mb-4">
                {{ session('success') }}
            </div>
        @endif

        @if(session('error'))
            <div class="bg-red-100 text-red-800 p-3 rounded mb-4">
                {{ session('error') }}
            </div>
        @endif

        <h2 class="text-xl font-semibold mb-4">Tutup Sesi Absensi</h2>

        @if($sesiAktif)
            <p class="mb-4 text-sm text-gray-700">
                Sesi saat ini dibuka pada: <strong>{{ \Carbon\Carbon::parse($sesiAktif->dibuka_pada)->format('d M Y H:i') }}</strong>
            </p>

            <form action="{{ route('juruarah.sesi.tutupSesi') }}" method="POST">
                @csrf
                <button type="submit" class="bg-red-600 text-white px-4 py-2 rounded hover:bg-red-700">
                    Tutup Sesi Sekarang
                </button>
            </form>
        @else
            <p class="text-sm text-gray-500">Tidak ada sesi absensi yang sedang berlangsung.</p>
        @endif
    </div>
@endsection
