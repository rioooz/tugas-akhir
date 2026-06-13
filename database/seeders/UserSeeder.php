<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // User Utama
        User::updateOrCreate(
            ['email' => 'user@gmail.com'],
            [
                'name' => 'user',
                'address' => 'Jl. Merdeka No. 10, Jakarta',
                'password' => Hash::make('user1234'),
                'email_verified_at' => now(),
                'role' => 'user',
            ]
        );

        // User Rio Baru
        User::updateOrCreate(
            ['email' => 'riopelanggan@gmail.com'],
            [
                'name' => 'Rio Pelanggan',
                'address' => 'Jl. Pemuda No. 12, Semarang',
                'password' => Hash::make('riouser123'),
                'email_verified_at' => now(),
                'role' => 'user',
            ]
        );
    }
}
