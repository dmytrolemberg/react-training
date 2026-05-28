<?php

declare(strict_types = 1);

namespace Database\Factories\Commerce;

use App\Models\User\User;
use App\Models\Commerce\Order;
use App\Models\Commerce\OrderStatus;
use App\Models\Commerce\PaymentStatus;
use App\Models\Commerce\DeliveryMethod;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<Order>
 */
class OrderFactory extends Factory
{
    protected $model = Order::class;

    /**
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $subtotal = fake()->numberBetween(5000, 40000);
        $delivery = 0;
        $tax = (int) round($subtotal * 0.08);

        return [
            'user_id' => User::factory(),
            'number' => 'NS-' . now()->format('Ymd') . '-' . fake()->unique()->numberBetween(1000, 9999),
            'status' => OrderStatus::Processing->value,
            'payment_status' => PaymentStatus::Paid->value,
            'subtotal_cents' => $subtotal,
            'delivery_cents' => $delivery,
            'tax_cents' => $tax,
            'total_cents' => $subtotal + $delivery + $tax,
            'contact_email' => fake()->safeEmail(),
            'contact_phone' => '+380000000000',
            'shipping_first_name' => fake()->firstName(),
            'shipping_last_name' => fake()->lastName(),
            'shipping_country' => 'Ukraine',
            'shipping_city' => 'Kyiv',
            'shipping_postal_code' => '01001',
            'shipping_address_line' => fake()->streetAddress(),
            'delivery_method' => DeliveryMethod::Standard->value,
            'payment_method_type' => 'mock_card',
            'payment_method_label' => 'Visa ending 4242',
            'transaction_id' => 'TX-' . fake()->unique()->numberBetween(1000, 9999),
            'placed_at' => now(),
        ];
    }
}
