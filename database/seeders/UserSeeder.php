<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // Membuat pengguna dengan role 'admin'
        User::create([
            'name' => 'Administrator',
            'email' => 'admin@gmail.com',
            'password' => Hash::make('admin'), // Pastikan untuk menggunakan password yang aman
            'role' => 'admin',
        ]);

        // Membuat pengguna dengan role 'petugas'
        User::create([
            'name' => 'Petugas',
            'email' => 'petugas@gmail.com',
            'password' => Hash::make('petugas'),
            'role' => 'petugas',
        ]);
    }
}
