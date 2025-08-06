<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Absensi extends Model
{
    protected $table = 'absensi'; // ← penting!

    protected $fillable = [
        'anggota_id',
        'sesi_absensi_id',
        'status',
        'keterangan',
        'tanggal',
        'jam',
    ];

    public function anggota()
    {
        return $this->belongsTo(Anggota::class);
    }

    public function absensi()
    {
        return $this->hasMany(\App\Models\Absensi::class, 'anggota_id');
    }

    public function sesiAbsensi()
    {
        return $this->belongsTo(\App\Models\SesiAbsensi::class, 'sesi_absensi_id');
    }
}

