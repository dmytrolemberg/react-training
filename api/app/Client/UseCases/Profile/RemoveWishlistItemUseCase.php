<?php

declare(strict_types = 1);

namespace App\Client\UseCases\Profile;

use App\Models\User\User;
use App\Models\Account\WishlistItem;

class RemoveWishlistItemUseCase
{
    public function execute(User $user, WishlistItem $wishlistItem): void
    {
        abort_unless($wishlistItem->user_id === $user->id, 404);

        $wishlistItem->delete();
    }
}
