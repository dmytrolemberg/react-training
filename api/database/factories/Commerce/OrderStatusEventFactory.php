<?php

declare(strict_types = 1);

namespace Database\Factories\Commerce;

use App\Models\Commerce\Order;
use App\Models\Commerce\OrderStatus;
use App\Models\Commerce\OrderStatusEvent;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<OrderStatusEvent>
 */
class OrderStatusEventFactory extends Factory
{
    protected $model = OrderStatusEvent::class;

    /**
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'order_id' => Order::factory(),
            'status' => OrderStatus::Processing->value,
            'label' => 'Order placed',
            'description' => fake()->sentence(),
            'occurred_at' => now(),
            'position' => 0,
        ];
    }
}
