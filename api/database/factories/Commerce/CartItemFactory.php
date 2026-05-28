<?php

declare(strict_types = 1);

namespace Database\Factories\Commerce;

use App\Models\Commerce\Cart;
use App\Models\Catalog\Product;
use App\Models\Commerce\CartItem;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<CartItem>
 */
class CartItemFactory extends Factory
{
    protected $model = CartItem::class;

    /**
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'cart_id' => Cart::factory(),
            'product_id' => Product::factory(),
            'quantity' => fake()->numberBetween(1, 3),
            'unit_price_cents' => fake()->numberBetween(1200, 20000),
        ];
    }
}
