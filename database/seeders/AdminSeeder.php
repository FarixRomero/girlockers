<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class AdminSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        User::create([
            'name' => 'Admin Girl Lockers',
            'email' => 'admin@girlslockers.com',
            'password' => Hash::make('password'),
            'role' => 'admin',
            'has_full_access' => true,
            'access_granted_at' => now(),
            'email_verified_at' => now(),
        ]);
        User::create([
            'name' => 'Admin Girl Lockers',
            'email' => 'user@girlslockers.com',
            'password' => Hash::make('password'),
            'role' => 'student',
            'has_full_access' => true,
            'access_granted_at' => now(),
            'email_verified_at' => now(),
        ]);
        User::create([
            'name' => 'Admin Girl Lockers',
            'email' => 'admin@admin.com',
            'password' => Hash::make('admin123'),
            'role' => 'admin',
            'has_full_access' => true,
            'access_granted_at' => now(),
            'email_verified_at' => now(),
        ]);
    }
}
