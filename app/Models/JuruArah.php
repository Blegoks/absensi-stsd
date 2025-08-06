<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\HasFactory;


class JuruArah extends Model
{
    //
    use HasFactory;

    protected $table = 'juruarah';

    protected $fillable = ['id', 'name'];

    public function tempekan()
{
    return $this->hasMany(Tempekan::class, 'juruarah_id');
}
public function user()
{
    return $this->hasOne(User::class, 'profile_id');
}




}
