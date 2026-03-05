<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use App\Models\User;

class AdminUserSeeder extends Seeder
{
    public function run(): void
    {
        User::create([
            'name' => 'Expert Admin',
            'email' => 'admin@etiopianmedicine.com',
            'password' => Hash::make('password123'),
            'role' => 'admin'
        ]);
    }
}
