<?php

declare(strict_types = 1);

namespace Database\Factories\Commerce;

use App\Models\User\User;
use App\Models\Commerce\Cart;
use App\Models\Commerce\CartStatus;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<Cart>
 */
class CartFactory extends Factory
{
    protected $model = Cart::class;

    /**
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'user_id' => User::factory(),
            'status' => CartStatus::Active->value,
            'currency' => 'USD',
        ];
    }
}
