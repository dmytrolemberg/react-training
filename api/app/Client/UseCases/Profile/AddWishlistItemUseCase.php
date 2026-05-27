<?php

declare(strict_types = 1);

namespace App\Client\UseCases\Profile;

use App\Models\User\User;
use App\Models\Catalog\Product;
use App\Models\Account\WishlistItem;
use Illuminate\Validation\ValidationException;

class AddWishlistItemUseCase
{
    /**
     * @throws ValidationException
     */
    public function execute(User $user, Product $product): WishlistItem
    {
        if (!$product->is_active) {
            throw ValidationException::withMessages(['product_id' => ['This product is not available.']]);
        }

        return WishlistItem::query()->firstOrCreate([
            'user_id' => $user->id,
            'product_id' => $product->id,
        ]);
    }
}
