<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    /**
     * Seed a default application user.
     */
    public function run(): void
    {
        User::firstOrCreate(
            ['email' => 'victorfm@live.com'],
            [
                'name' => 'Victor Martins',
                'password' => Hash::make('password'),
            ]
        );
    }
}
