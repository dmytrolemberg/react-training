<?php

declare(strict_types = 1);

namespace Database\Factories\Catalog;

use Illuminate\Support\Str;
use App\Models\Catalog\Brand;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<Brand>
 */
class BrandFactory extends Factory
{
    protected $model = Brand::class;

    /**
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $name = fake()->unique()->company();

        return [
            'slug' => Str::slug($name),
            'name' => $name,
            'description' => fake()->sentence(10),
            'logo_initial' => Str::upper(Str::substr($name, 0, 1)),
            'is_active' => true,
        ];
    }

    public function inactive(): static
    {
        return $this->state(fn(array $attributes): array => [
            'is_active' => false,
        ]);
    }
}
