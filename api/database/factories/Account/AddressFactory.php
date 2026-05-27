<?php

declare(strict_types = 1);

namespace Database\Factories\Account;

use App\Models\User\User;
use App\Models\Account\Address;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<Address>
 */
class AddressFactory extends Factory
{
    protected $model = Address::class;

    /**
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'user_id' => User::factory(),
            'label' => fake()->randomElement(['Home', 'Work']),
            'first_name' => fake()->firstName(),
            'last_name' => fake()->lastName(),
            'phone' => '+380000000000',
            'country' => 'Ukraine',
            'city' => 'Kyiv',
            'postal_code' => '01001',
            'address_line' => fake()->streetAddress(),
            'is_default' => false,
        ];
    }

    public function default(): static
    {
        return $this->state(fn(array $attributes): array => [
            'is_default' => true,
        ]);
    }
}
