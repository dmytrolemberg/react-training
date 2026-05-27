<?php

declare(strict_types = 1);

namespace App\Client\UseCases\Cart;

use App\Models\User\User;
use App\Models\Commerce\Cart;
use App\Client\Services\Cart\CartResolver;

class ClearCartUseCase
{
    public function __construct(
        private readonly CartResolver $cartResolver,
    ) {}

    public function execute(User $user): Cart
    {
        $cart = $this->cartResolver->activeForUser($user);
        $cart->items()->delete();

        return $cart->refresh()->load(['items.product.brand', 'items.product.category', 'items.product.images']);
    }
}
