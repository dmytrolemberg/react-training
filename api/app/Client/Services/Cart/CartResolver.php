<?php

declare(strict_types = 1);

namespace App\Client\Services\Cart;

use App\Models\User\User;
use App\Models\Commerce\Cart;

class CartResolver
{
    public function activeForUser(User $user): Cart
    {
        return Cart::query()->firstOrCreate(['user_id' => $user->id]);
    }
}
