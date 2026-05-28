<?php

declare(strict_types = 1);

namespace Database\Seeders;

use App\Models\User\User;
use App\Models\User\UserRole;
use Illuminate\Database\Seeder;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;

class DatabaseSeeder extends Seeder
{
    use WithoutModelEvents;

    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        User::query()->updateOrCreate(
            ['email' => 'user@example.com'],
            [
                'first_name' => 'Dmytro',
                'last_name' => 'Orikhovskyi',
                'avatar_path' => '/images/profiles/dmytro-orikhovskyi.jpg',
                'phone' => '+380000000000',
                'country' => 'Ukraine',
                'city' => 'Kyiv',
                'postal_code' => '01001',
                'address_line' => 'Street address placeholder',
                'password' => 'password',
                'role' => UserRole::User->value,
                'email_verified_at' => now(),
            ],
        );

        User::query()->updateOrCreate(
            ['email' => 'admin@example.com'],
            [
                'first_name' => 'Test',
                'last_name' => 'Admin',
                'avatar_path' => '/images/profiles/test-admin.jpg',
                'password' => 'password',
                'role' => UserRole::Admin->value,
                'email_verified_at' => now(),
            ],
        );

        $this->call(ShopSeeder::class);
    }
}
