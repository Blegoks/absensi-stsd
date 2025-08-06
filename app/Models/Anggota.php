<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Anggota extends Model
{
    use HasFactory;

    protected $table = 'anggota';

    protected $fillable = [
        'user_id', 'juru_arah_id', 'nama', 'email', 'Tempekan', 'foto',
    ];

    public function absensi()
    {
        return $this->hasMany(Absensi::class, 'anggota_id');
    }

    public function juruArah()
    {
        return $this->belongsTo(JuruArah::class, 'juru_arah_id');
    }

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    // Pastikan hanya aktifkan jika model Tempekan ada
    public function tempekan()
    {
        return $this->belongsTo(Tempekan::class);
    }
}
