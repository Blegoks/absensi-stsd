<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    use HasFactory, Notifiable;

    protected $fillable = [
        'name',
        'email',
        'password',
        'role',
        'profile_id',
        'activation_token',
        'is_active',
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
        ];
    }

    // Relasi ke admin
    public function admin()
    {
        return $this->belongsTo(Admin::class, 'profile_id');
    }

    // Relasi ke juru arah
    public function juruarah()
    {
        return $this->belongsTo(JuruArah::class, 'profile_id');
    }

    // Relasi ke anggota
   public function anggota()
{
    return $this->hasOne(Anggota::class, 'user_id', 'id','profile_id'); // atau sesuaikan kolom foreign key-nya
}

    public function tempekan()
    {
        return $this->belongsTo(\App\Models\Tempekan::class, 'tempekan_id');
    }

}
