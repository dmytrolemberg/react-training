<?php

declare(strict_types = 1);

namespace Database\Factories\Account;

use App\Models\User\User;
use App\Models\Catalog\Product;
use App\Models\Account\WishlistItem;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<WishlistItem>
 */
class WishlistItemFactory extends Factory
{
    protected $model = WishlistItem::class;

    /**
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'user_id' => User::factory(),
            'product_id' => Product::factory(),
        ];
    }
}
