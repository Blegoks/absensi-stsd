<?php

namespace App\Exports;

use App\Models\Anggota;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Carbon\Carbon;

class AbsensiAnggotaExport implements FromCollection, WithHeadings
{
   protected $anggota_id, $tanggal_mulai, $tanggal_selesai;

public function __construct($anggota_id, $tanggal_mulai = null, $tanggal_selesai = null)
{
    $this->anggota_id = $anggota_id;
    $this->tanggal_mulai = $tanggal_mulai;
    $this->tanggal_selesai = $tanggal_selesai;
}

public function collection()
{
    $anggota = Anggota::with(['absensi.sesiAbsensi' => function ($q) {
        if ($this->tanggal_mulai && $this->tanggal_selesai) {
            $q->whereBetween('tanggal', [$this->tanggal_mulai, $this->tanggal_selesai]);
        }
    }])->findOrFail($this->anggota_id);

    return $anggota->absensi->map(function ($absen) {
        return [
            'tanggal' => Carbon::parse($absen->tanggal)->format('d-m-Y'),
            'status' => ucfirst($absen->status),
            'jenis_kegiatan' => $absen->sesiAbsensi->jenis_kegiatan ?? '-',
        ];
    });
}

public function headings(): array
{
    return ['Tanggal', 'Status', 'Jenis Kegiatan'];
}

}
