<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class SesiAbsensi extends Model
{
    protected $table = 'sesi_absensi';

    protected $fillable = [
        'juruarah_id',
        'dibuka_pada',
        'ditutup_pada',
        'is_open',
        'jenis_kegiatan'
    ];
    public function juruArah()
    {
        return $this->belongsTo(User::class, 'juruarah_id');
    }
    public function absensi()
    {
        return $this->hasMany(Absensi::class, 'sesi_absensi_id');
    }
}
