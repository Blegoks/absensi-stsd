<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class CatatanAbsensi extends Model
{

    protected $table = 'catatan_absensi';

    public function anggota()
    {
        return $this->belongsTo(\App\Models\Anggota::class, 'anggota_id');
    }

}
