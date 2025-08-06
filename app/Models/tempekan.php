<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class tempekan extends Model
{
    //
    use HasFactory;

    protected $table = 'tempekan';

    protected $fillable = ['id', 'nama'];

    public function anggota()
{
    return $this->hasMany(Anggota::class);
}

public function juruarah()
{
    return $this->belongsTo(JuruArah::class, 'juruarah_id');
}


}
