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
        User::create([
            'name' => 'user',
            'email' => 'user@gmail.com',
            'address' => 'Jl. Merdeka No. 10, Jakarta',
            'password' => Hash::make('user1234'),
            'email_verified_at' => now(),
            'role' => 'user',
        ]);
    }
}
