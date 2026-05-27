<?php

declare(strict_types = 1);

namespace App\Client\Services\Cart;

use App\Models\User\User;
use App\Models\Commerce\Cart;
use App\Models\Commerce\CartStatus;

class CartResolver
{
    public function activeForUser(User $user): Cart
    {
        $cart = Cart::query()
            ->where('user_id', $user->id)
            ->where('status', CartStatus::Active->value)
            ->first();

        if ($cart instanceof Cart) {
            return $cart;
        }

        return Cart::query()->create([
            'user_id' => $user->id,
            'status' => CartStatus::Active->value,
            'currency' => 'USD',
        ]);
    }
}
