<table>
    <thead>
        <tr>
            <th>Nama</th>
            @foreach($tanggalList as $tgl)
                <th>{{ \Carbon\Carbon::parse($tgl)->format('d-m-Y') }}</th>
            @endforeach
        </tr>
    </thead>
    <tbody>
        @foreach($anggota as $a)
        <tr>
            <td>{{ $a->nama }}</td>
            @foreach($tanggalList as $tgl)
                @php
                    $absen = $a->absensi->firstWhere('tanggal', $tgl);
                    $izin = $a->izin->firstWhere('tanggal', $tgl);
                @endphp
                <td>
                    @if($absen)
                        Hadir
                    @elseif($izin && $izin->status == 'diterima')
                        Izin
                    @elseif($izin && $izin->status == 'pending')
                        Izin (Pending)
                    @elseif($izin && $izin->status == 'ditolak')
                        Izin (Ditolak)
                    @else
                        Tidak Hadir
                    @endif
                </td>
            @endforeach
        </tr>
        @endforeach
    </tbody>
</table>
