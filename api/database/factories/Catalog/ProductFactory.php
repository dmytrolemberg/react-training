<?php

declare(strict_types = 1);

namespace Database\Factories\Catalog;

use Illuminate\Support\Str;
use App\Models\Catalog\Brand;
use App\Models\Catalog\Product;
use App\Models\Catalog\Category;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<Product>
 */
class ProductFactory extends Factory
{
    protected $model = Product::class;

    /**
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $name = fake()->unique()->bothify('Product ###');

        return [
            'brand_id' => Brand::factory(),
            'category_id' => Category::factory(),
            'slug' => Str::slug($name) . '-' . fake()->unique()->numberBetween(1000, 9999),
            'sku' => Str::upper(fake()->unique()->bothify('SKU-####-??')),
            'name' => Str::title($name),
            'short_description' => fake()->sentence(12),
            'description_html' => '<p>' . fake()->paragraph() . '</p>',
            'price_cents' => fake()->numberBetween(1200, 25000),
            'currency' => 'USD',
            'stock_quantity' => fake()->numberBetween(1, 30),
            'is_active' => true,
            'rating_average' => fake()->randomFloat(1, 3.5, 5),
            'reviews_count' => fake()->numberBetween(0, 250),
        ];
    }

    public function inactive(): static
    {
        return $this->state(fn(array $attributes): array => [
            'is_active' => false,
        ]);
    }

    public function outOfStock(): static
    {
        return $this->state(fn(array $attributes): array => [
            'stock_quantity' => 0,
        ]);
    }
}
