<?php

declare(strict_types = 1);

namespace Database\Factories\Catalog;

use App\Models\Catalog\Product;
use App\Models\Catalog\ProductImage;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<ProductImage>
 */
class ProductImageFactory extends Factory
{
    protected $model = ProductImage::class;

    /**
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'product_id' => Product::factory(),
            'url' => fake()->imageUrl(width: 900, height: 900),
            'alt' => fake()->sentence(4),
            'position' => fake()->numberBetween(0, 4),
            'is_primary' => false,
        ];
    }

    public function primary(): static
    {
        return $this->state(fn(array $attributes): array => [
            'position' => 0,
            'is_primary' => true,
        ]);
    }
}
