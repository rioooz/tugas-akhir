<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class AdminSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Admin Utama
        User::updateOrCreate(
            ['email' => 'admin@gmail.com'],
            [
                'name' => 'Admin',
                'address' => 'Jl. Raya No. 1, Jakarta',
                'password' => Hash::make('admin123'),
                'email_verified_at' => now(),
                'role' => 'admin',
            ]
        );

        // Admin Rio Baru
        User::updateOrCreate(
            ['email' => 'adminrio@gmail.com'],
            [
                'name' => 'Admin Rio',
                'address' => 'Jl. Mebel Raya No. 5, Jepara',
                'password' => Hash::make('rioadmin123'),
                'email_verified_at' => now(),
                'role' => 'admin',
            ]
        );
    }
}
