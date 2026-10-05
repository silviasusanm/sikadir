<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    public function run(): void
    {
        // Akun Admin / Pemilik
        User::updateOrCreate(
            ['email' => 'admin@sikadir.com'],
            [
                'name' => 'Putri Admin',
                'password' => Hash::make('password123'), // Password di-hash
                'role' => 'admin',
            ]
        );

        // Akun Kasir
        User::updateOrCreate(
            ['email' => 'kasir@sikadir.com'],
            [
                'name' => 'Silvia Kasir',
                'password' => Hash::make('password123'), // Password di-hash
                'role' => 'kasir',
            ]
        );
    }
}
