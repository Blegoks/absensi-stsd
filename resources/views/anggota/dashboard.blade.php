@extends('anggota.layout')

@section('title', 'Dashboard Anggota')

@section('content')
    @if ($sesiAktif)
        <div class="mb-6">
            <h2 class="text-lg font-semibold text-green-700">Sesi Absensi Aktif</h2>
            <p>Absensi dibuka pada: {{ \Carbon\Carbon::parse($sesiAktif->dibuka_pada)->format('d M Y H:i') }}</p>
            <p>Status: <span class="text-green-600 font-bold">Dibuka</span></p>
        </div>

        @if ($absensiHariIni)
            <div class="bg-green-100 p-4 rounded shadow">
                <p class="text-green-700">Kamu sudah melakukan absensi hari ini.</p>
            </div>
        @else
            <div class="bg-yellow-100 p-4 rounded shadow">
                <p class="text-yellow-700">Kamu belum melakukan absensi hari ini.</p>
            </div>
        @endif
    @else
        <div class="bg-red-100 p-4 rounded shadow mb-6">
            <p class="text-red-700">Belum ada sesi absensi yang aktif saat ini.</p>
        </div>
    @endif

    <div class="mt-8">
        <h2 class="text-lg font-semibold">Riwayat Absensi Terakhir</h2>
        <ul class="mt-2 space-y-2">
            @forelse ($riwayat as $absen)
                <li class="bg-white p-3 rounded shadow text-sm">
                    {{ \Carbon\Carbon::parse($absen->tanggal)->format('d M Y') }} - 
                    Status: <strong>{{ ucfirst($absen->status) }}</strong>
                </li>
            @empty
                <li class="text-gray-500">Belum ada riwayat absensi.</li>
            @endforelse
        </ul>
    </div>
@endsection
