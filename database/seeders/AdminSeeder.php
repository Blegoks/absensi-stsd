<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use App\Models\Admin;
use Illuminate\Support\Facades\Hash;

class AdminSeeder extends Seeder
{
    public function run()
    {
       $adminList = Admin::all();

        foreach ($adminList as $index => $admin) {
            User::updateOrCreate(
                ['profile_id' => $admin->id, 'role' => 'admin'],
                [
                    'name' => $admin->name ?? 'admin ' . ($index + 1),
                    'email' => $admin->name . ($index + 1) . '@gmail.com',
                    'password' => Hash::make('password'),
                    'role' => 'admin',
                    'profile_id' => $admin->id
                ]
            );
        }
    }
}
