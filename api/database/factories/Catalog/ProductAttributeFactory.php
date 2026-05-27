<?php

declare(strict_types = 1);

namespace Database\Factories\Catalog;

use App\Models\Catalog\Product;
use App\Models\Catalog\ProductAttribute;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<ProductAttribute>
 */
class ProductAttributeFactory extends Factory
{
    protected $model = ProductAttribute::class;

    /**
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'product_id' => Product::factory(),
            'name' => fake()->randomElement(['Material', 'Color', 'Size', 'Finish', 'Warranty']),
            'value' => fake()->word(),
            'position' => fake()->numberBetween(1, 50),
        ];
    }
}
