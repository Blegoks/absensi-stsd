<?php

namespace App\Exports;

use App\Models\Anggota;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;

class RekapAnggotaExport implements FromCollection, WithHeadings
{
    protected $juruArahId;
    protected $mulai;
    protected $selesai;

    public function __construct($juruArahId, $mulai = null, $selesai = null)
    {
        $this->juruArahId = $juruArahId;
        $this->mulai = $mulai;
        $this->selesai = $selesai;
    }

    public function collection()
    {
        $anggotaList = Anggota::where('juru_arah_id', $this->juruArahId)
            ->with(['absensi' => function ($q) {
                if ($this->mulai && $this->selesai) {
                    $q->whereBetween('tanggal', [$this->mulai, $this->selesai]);
                }
            }])->get();

        return $anggotaList->map(function ($anggota) {
            return [
                'Nama Anggota' => $anggota->nama,
                'Total Hadir' => $anggota->absensi->where('status', 'hadir')->count(),
                'Total Izin' => $anggota->absensi->where('status', 'izin')->count(),
                'Total Tidak Hadir' => $anggota->absensi->where('status', 'alpha')->count(),
            ];
        });
    }

    public function headings(): array
    {
        return ['Nama Anggota', 'Total Hadir', 'Total Izin', 'Total Tidak Hadir'];
    }
}

