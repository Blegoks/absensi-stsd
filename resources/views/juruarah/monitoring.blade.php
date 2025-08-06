@extends('juruarah.dashboard')

@section('title', 'Monitoring Absensi')

@section('content')
    @if ($sesiAktif && count($sesiAktif) > 0)
        @foreach ($sesiAktif as $sesi)
            <div class="mb-4">
                <h2 class="text-xl font-semibold">
                    Sesi Aktif: {{ $sesi->jenis_kegiatan }} ({{ \Carbon\Carbon::parse($sesi->dibuka_pada)->format('d M Y H:i') }})
                </h2>
            </div>

            <div class="overflow-x-auto bg-white rounded shadow mb-8">
                <table class="min-w-full text-sm">
                    <thead class="bg-gray-200">
                        <tr>
                            <th class="px-4 py-2 text-left">Nama</th>
                            <th class="px-4 py-2 text-left">Status Kehadiran</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($anggota as $a)
                            @php
                                $absen = $a->absensi->firstWhere('sesi_absensi_id', $sesi->id);
                            @endphp
                            <tr class="border-b">
                                <td class="px-4 py-2">{{ $a->nama }}</td>
                              <td class="px-4 py-2">
                                    @if ($absen)
                                        @if ($absen->status === 'hadir')
                                            <span class="text-green-600 font-semibold">Hadir</span>
                                        @elseif ($absen->status === 'izin')
                                            <span class="text-yellow-600 font-semibold">Izin</span>
                                        @elseif ($absen->status === 'alpa')
                                            <span class="text-red-600 font-semibold">Tidak Hadir</span>
                                        @else
                                            <span class="text-gray-600 font-semibold">Status: {{ $absen->status }}</span>
                                        @endif
                                    @else
                                        {{-- Default: belum absen = dianggap tidak hadir --}}
                                        <span class="text-red-600 font-semibold">Tidak Hadir</span>

                                        {{-- Tampilkan form izin --}}
                                        <form action="{{ route('juruarah.absensi.izin.store') }}" method="POST" class="flex items-center gap-2 mt-1">
                                            @csrf
                                            <input type="hidden" name="anggota_id" value="{{ $a->id }}">
                                            <input type="hidden" name="sesi_absensi_id" value="{{ $sesi->id }}">
                                            <input type="text" name="keterangan" placeholder="Alasan Izin" required class="text-xs border rounded px-2 py-1">
                                            <button type="submit" class="bg-blue-600 text-white px-3 py-1 text-xs rounded">Ubah ke Izin</button>
                                        </form>
                                    @endif
                                </td>

                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        @endforeach
    @else
        <div class="p-4 bg-yellow-100 text-yellow-800 rounded">
            {{ $message ?? 'Belum ada sesi absensi yang dibuka.' }}
        </div>
    @endif
@endsection
