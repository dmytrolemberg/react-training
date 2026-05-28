<?php

declare(strict_types = 1);

namespace Database\Factories\Commerce;

use App\Models\User\User;
use App\Models\Commerce\Order;
use App\Models\Commerce\CheckoutSession;
use App\Models\Commerce\CheckoutSessionStatus;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<CheckoutSession>
 */
class CheckoutSessionFactory extends Factory
{
    protected $model = CheckoutSession::class;

    /**
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $subtotal = fake()->numberBetween(5000, 40000);
        $tax = (int) round($subtotal * 0.08);

        return [
            'user_id' => User::factory(),
            'cart_id' => null,
            'order_id' => Order::factory(),
            'status' => CheckoutSessionStatus::Completed->value,
            'failure_stage' => null,
            'failure_reason' => null,
            'contact_email' => fake()->safeEmail(),
            'delivery_method' => 'standard',
            'payment_method_type' => 'mock_card',
            'subtotal_cents' => $subtotal,
            'delivery_cents' => 0,
            'tax_cents' => $tax,
            'total_cents' => $subtotal + $tax,
            'item_count' => fake()->numberBetween(1, 5),
            'payload' => ['contact' => ['email' => fake()->safeEmail()]],
            'completed_at' => now(),
            'failed_at' => null,
        ];
    }

    public function failed(): static
    {
        return $this->state(fn(array $attributes): array => [
            'order_id' => null,
            'status' => CheckoutSessionStatus::Failed->value,
            'failure_stage' => 'checkout',
            'failure_reason' => 'Cart could not be checked out.',
            'completed_at' => null,
            'failed_at' => now(),
        ]);
    }
}
