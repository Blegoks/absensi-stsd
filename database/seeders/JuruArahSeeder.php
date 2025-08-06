<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\JuruArah;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class JuruArahSeeder extends Seeder
{
    public function run()
    {
        $juruarahList = JuruArah::all();

        foreach ($juruarahList as $index => $juruarah) {
            User::updateOrCreate(
                ['profile_id' => $juruarah->id, 'role' => 'juruarah'],
                [
                    'name' => $juruarah->name ?? 'Juru Arah ' . ($index + 1),
                    'email' => $juruarah->name . ($index + 1) . '@gmail.com',
                    'password' => Hash::make('password'),
                    'role' => 'juruarah',
                    'profile_id' => $juruarah->id
                ]
            );
        }
    }
}

