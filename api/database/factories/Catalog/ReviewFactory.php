<?php

declare(strict_types = 1);

namespace Database\Factories\Catalog;

use App\Models\User\User;
use App\Models\Catalog\Review;
use App\Models\Catalog\Product;
use App\Models\Catalog\ReviewStatus;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<Review>
 */
class ReviewFactory extends Factory
{
    protected $model = Review::class;

    /**
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'product_id' => Product::factory(),
            'user_id' => User::factory(),
            'order_item_id' => null,
            'rating' => fake()->numberBetween(3, 5),
            'body' => fake()->paragraph(),
            'author_name' => fake()->firstName(),
            'status' => ReviewStatus::Approved->value,
            'is_verified_purchase' => false,
        ];
    }

    public function pending(): static
    {
        return $this->state(fn(array $attributes): array => [
            'status' => ReviewStatus::Pending->value,
        ]);
    }
}
