<?php

declare(strict_types = 1);

namespace App\Client\UseCases\Cart;

use App\Models\User\User;
use App\Models\Commerce\Cart;
use App\Models\Commerce\CartItem;

class RemoveCartItemUseCase
{
    public function execute(User $user, CartItem $cartItem): Cart
    {
        $cartItem->loadMissing('cart');
        $cart = $cartItem->cart;

        abort_unless($cart->user_id === $user->id, 404);

        $cartItem->delete();

        return $cart->refresh()->load(['items.product.brand', 'items.product.category', 'items.product.images']);
    }
}
