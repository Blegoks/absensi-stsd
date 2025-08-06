<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Admin extends Model
{
    //
     use HasFactory;

    protected $table = 'admins';

    protected $fillable = ['id', 'name'];

    public function user()
{
    return $this->hasOne(\App\Models\User::class, 'profile_id');
}
public function juruarah()
{
    return $this->belongsTo(User::class, 'juruarah_id');
}

    public function anggota()
    {
        return $this->belongsTo(User::class, 'anggota_id');
    }


    
}
