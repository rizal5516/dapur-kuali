<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use App\Models\User;

class AdminUserSeeder extends Seeder
{
    public function run(): void
    {
        // Admin
        User::query()->updateOrCreate(
            ['email' => 'rizalqowi17@gmail.com'],
            [
                'name' => 'Admin CMS',
                'password' => Hash::make('Admin12345!'), // ganti setelah pertama login
                'role' => 'admin',
                'remember_token' => Str::random(10),
            ]
        );

        // Editor
        User::query()->updateOrCreate(
            ['email' => 'dawneyjunior17@gmail.com'],
            [
                'name' => 'Editor CMS',
                'password' => Hash::make('Editor12345!'),
                'role' => 'editor',
                'remember_token' => Str::random(10),
            ]
        );
    }
}
