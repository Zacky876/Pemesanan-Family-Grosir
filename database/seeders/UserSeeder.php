<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    public function run(): void
    {
        User::create([
            'name' => 'Administrator',
            'email' => 'admin@minimarket.com',
            'password' => Hash::make('admin123'),
            'role' => 'admin',
        ]);

        User::create([
            'name' => 'Kasir',
            'email' => 'kasir@minimarket.com',
            'password' => Hash::make('kasir123'),
            'role' => 'kasir',
        ]);

        User::create([
            'name' => 'Pelanggan',
            'email' => 'user@minimarket.com',
            'password' => Hash::make('user123'),
            'role' => 'user',
        ]);
    }
}