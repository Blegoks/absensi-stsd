<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Anggota;
use App\Models\Tempekan;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class AnggotaSeeder extends Seeder
{
    public function run()
    {
        $tempekanList = Tempekan::all();

        if ($tempekanList->isEmpty()) {
            dd("Data tempekan belum tersedia. Harap isi tabel tempekan terlebih dahulu.");
        }

        foreach ($tempekanList as $i => $tempekan) {
            // Buat anggota
            $anggota = Anggota::create([
                'nama' => 'Anggota ' . ($i + 1),
                'email' => 'anggota' . ($i + 1) . '@gmail.com',
                'tempekan_id' => $tempekan->id,
            ]);

            // Buat user terkait
            User::create([
                'name' => $anggota->nama,
                'email' => $anggota->email,
                'password' => Hash::make('password'), // default password
                'role' => 'anggota',
                'profile_id' => $anggota->id
            ]);
        }
    }
}
