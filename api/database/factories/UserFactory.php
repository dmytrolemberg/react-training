<?php

declare(strict_types = 1);

namespace Database\Factories;

use App\Models\User\User;
use Illuminate\Support\Str;
use App\Models\User\UserRole;
use Illuminate\Support\Facades\Hash;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<User>
 */
class UserFactory extends Factory
{
    /**
     * @var class-string<User>
     */
    protected $model = User::class;

    /**
     * The current password being used by the factory.
     */
    protected static ?string $password;

    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'first_name' => fake()->firstName(),
            'last_name' => fake()->lastName(),
            'email' => fake()->unique()->safeEmail(),
            'avatar_path' => fake()->optional()->imageUrl(320, 320),
            'phone' => '+380000000000',
            'country' => 'Ukraine',
            'city' => 'Kyiv',
            'postal_code' => '01001',
            'address_line' => fake()->streetAddress(),
            'email_verified_at' => now(),
            'password' => static::$password ??= Hash::make('password'),
            'role' => UserRole::User->value,
            'remember_token' => Str::random(10),
        ];
    }

    public function admin(): static
    {
        return $this->state(fn(array $attributes): array => [
            'role' => UserRole::Admin->value,
        ]);
    }

    public function user(): static
    {
        return $this->state(fn(array $attributes): array => [
            'role' => UserRole::User->value,
        ]);
    }

    /**
     * Indicate that the model's email address should be unverified.
     */
    public function unverified(): static
    {
        return $this->state(fn(array $attributes): array => [
            'email_verified_at' => null,
        ]);
    }
}
