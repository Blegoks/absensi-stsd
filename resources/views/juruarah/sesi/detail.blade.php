@foreach($anggotaList as $anggota)
<tr>
    <td>{{ $anggota->nama }}</td>
    <td>
        @php
            $hadir = $anggota->absensi->where('sesi_absensi_id', $sesi->id)->first();
        @endphp

        @if($hadir)
            <span class="text-green-600 font-semibold">Hadir</span>
        @else
            <span class="text-red-600 font-semibold">Tidak Hadir</span>
        @endif
    </td>
    <td>
        {{-- Tombol ubah jadi izin --}}
        <form action="{{ route('juruarah.absensi.izin') }}" method="POST">
            @csrf
            <input type="hidden" name="anggota_id" value="{{ $anggota->id }}">
            <input type="hidden" name="sesi_absensi_id" value="{{ $sesi->id }}">
            <button type="submit" class="text-blue-600 hover:underline text-xs">Jadikan Izin</button>
        </form>
    </td>
</tr>
@endforeach
