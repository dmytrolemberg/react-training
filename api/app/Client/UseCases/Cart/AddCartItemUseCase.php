<?php

declare(strict_types = 1);

namespace App\Client\UseCases\Cart;

use App\Models\User\User;
use App\Models\Commerce\Cart;
use App\Models\Catalog\Product;
use App\Models\Commerce\CartItem;
use App\Client\Services\Cart\CartResolver;
use App\Client\Services\Product\InventoryAvailabilityService;

class AddCartItemUseCase
{
    public function __construct(
        private readonly CartResolver $cartResolver,
        private readonly InventoryAvailabilityService $inventoryAvailability,
    ) {}

    public function execute(User $user, Product $product, int $quantity): Cart
    {
        $cart = $this->cartResolver->activeForUser($user);
        $cartItem = $cart->items()->where('product_id', $product->id)->first();
        $newQuantity = $quantity + ($cartItem instanceof CartItem ? $cartItem->quantity : 0);

        $this->inventoryAvailability->ensureProductCanBePurchased($product, $newQuantity);

        if ($cartItem instanceof CartItem) {
            $cartItem->forceFill([
                'quantity' => $newQuantity,
                'unit_price_cents' => $product->price_cents,
            ])->save();
        } else {
            $cart->items()->create([
                'product_id' => $product->id,
                'quantity' => $quantity,
                'unit_price_cents' => $product->price_cents,
            ]);
        }

        return $cart->refresh()->load(['items.product.images', 'items.product.attributes']);
    }
}
