<?php

declare(strict_types = 1);

namespace Database\Factories\Shop;

use App\Models\Shop\ShopSetting;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<ShopSetting>
 */
class ShopSettingFactory extends Factory
{
    protected $model = ShopSetting::class;

    /**
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'key' => fake()->unique()->word(),
            'value' => ['enabled' => true],
        ];
    }
}
