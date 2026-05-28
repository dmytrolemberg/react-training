<?php

declare(strict_types = 1);

namespace App\Client\UseCases\Cart;

use App\Models\User\User;
use App\Models\Commerce\Cart;
use App\Models\Commerce\CartItem;
use App\Client\Services\Product\InventoryAvailabilityService;

class UpdateCartItemQuantityUseCase
{
    public function __construct(
        private readonly InventoryAvailabilityService $inventoryAvailability,
    ) {}

    public function execute(User $user, CartItem $cartItem, int $quantity): Cart
    {
        $cartItem->loadMissing(['cart', 'product']);
        $cart = $cartItem->cart;

        abort_unless($cart->user_id === $user->id, 404);

        $this->inventoryAvailability->ensureProductCanBePurchased($cartItem->product, $quantity);

        $cartItem->forceFill([
            'quantity' => $quantity,
            'unit_price_cents' => $cartItem->product->price_cents,
        ])->save();

        return $cart->refresh()->load(['items.product.images', 'items.product.attributes']);
    }
}
