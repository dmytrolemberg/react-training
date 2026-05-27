<?php

declare(strict_types = 1);

namespace Database\Factories\Account;

use App\Models\User\User;
use App\Models\Account\PaymentMethod;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<PaymentMethod>
 */
class PaymentMethodFactory extends Factory
{
    protected $model = PaymentMethod::class;

    /**
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'user_id' => User::factory(),
            'type' => 'mock_card',
            'label' => 'Visa ending 4242',
            'brand' => 'Visa',
            'last_four' => '4242',
            'expires_month' => 12,
            'expires_year' => 2028,
            'mock_token' => fake()->unique()->bothify('pm_mock_########'),
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
