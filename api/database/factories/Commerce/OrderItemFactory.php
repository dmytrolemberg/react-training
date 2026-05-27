<?php

declare(strict_types = 1);

namespace Database\Factories\Commerce;

use App\Models\Commerce\Order;
use App\Models\Catalog\Product;
use App\Models\Commerce\OrderItem;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<OrderItem>
 */
class OrderItemFactory extends Factory
{
    protected $model = OrderItem::class;

    /**
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $quantity = fake()->numberBetween(1, 3);
        $price = fake()->numberBetween(1200, 20000);

        return [
            'order_id' => Order::factory(),
            'product_id' => Product::factory(),
            'product_slug' => fake()->slug(),
            'sku' => fake()->bothify('SKU-####-??'),
            'name' => fake()->words(3, true),
            'brand_name' => fake()->company(),
            'category_name' => fake()->word(),
            'unit_price_cents' => $price,
            'quantity' => $quantity,
            'total_cents' => $price * $quantity,
            'attributes' => [
                ['name' => 'Color', 'value' => 'Graphite'],
            ],
        ];
    }
}
