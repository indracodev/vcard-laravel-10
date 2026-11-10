<?php

namespace Database\Seeders;

use App\Enums\UserRole;
use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    use WithoutModelEvents;

    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        User::firstOrCreate(
            ['name' => 'admin'],
            [
                'email' => 'admin@indracoglobal.com',
                'role' => UserRole::Admin,
                'password' => 'admin123',
            ]
        );
    }
}
