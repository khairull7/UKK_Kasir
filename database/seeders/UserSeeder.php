<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class UserSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        if (!User::where('email', 'admin@gmail.com')->exists()) {
            User::create([
                'name' => 'Admin',
                'email' => 'admin@gmail.com',
                'password' => Hash::make('password'), 
                'role' => 'admin',
                'email_verified_at' => now(),
                'remember_token' => Str::random(10),    
            ]);
        }

        if (!User::where('email', 'staff@gmail.com')->exists()) {
            User::create([
                'name' => 'Staff', 
                'email' => 'staff@gmail.com', 
                'password' => Hash::make('password'),
                'role' => 'staff',   
                'email_verified_at' => now(),
                'remember_token' => Str::random(10),
            ]);
        }
    }
}
