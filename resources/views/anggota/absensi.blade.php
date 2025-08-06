@extends('anggota.layout')

@section('title', 'Dashboard Anggota')

@section('content')
    <div class="bg-white p-6 rounded shadow">
        <h2 class="text-lg font-semibold mb-4">Status Absensi Hari Ini</h2>
        @if(session('success')) <div class="text-green-600 mb-3">{{ session('success') }}</div> @endif
        @if(session('error')) <div class="text-red-600 mb-3">{{ session('error') }}</div> @endif

        @if($absensiHariIni)
            <p> Anda sudah absen hari ini sebagai <strong>{{ $absensiHariIni->status }}</strong>.</p>
        @else
            @if($sesiAktif)
                <p class="mb-4"> Sesi absensi sedang aktif.</p>
                <a href="{{ route('user.scan') }}" class="bg-blue-500 text-white px-4 py-2 rounded">Mulai Scan Wajah</a>

                <!-- Form Ajukan Izin -->
                <div class="mt-6 bg-yellow-50 p-4 rounded shadow">
                    <h3 class="font-semibold mb-2">Ajukan Izin Tidak Hadir</h3>
                    <form action="{{ route('anggota.izin.ajukan') }}" method="POST" enctype="multipart/form-data" class="space-y-2">
                        @csrf
                        <div>
                            <label for="alasan" class="block text-sm">Alasan</label>
                            <input type="text" name="alasan" id="alasan" class="border rounded w-full p-1" required>
                        </div>
                        <div>
                            <label for="bukti" class="block text-sm">Upload Bukti (opsional)</label>
                            <input type="file" name="bukti" id="bukti" class="border rounded w-full p-1">
                        </div>
                        <button type="submit" class="bg-blue-600 text-white px-4 py-2 rounded">Ajukan Izin</button>
                    </form>
                </div>
                <!-- End Form Ajukan Izin -->
            @else
                <p> Belum ada sesi absensi aktif.</p>
            @endif
        @endif
    </div>
@endsection
