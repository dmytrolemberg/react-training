<?php

declare(strict_types = 1);

namespace App\Client\Resources\Api\V1\Profile;

use Illuminate\Http\Request;
use App\Models\Account\WishlistItem;
use Illuminate\Http\Resources\Json\JsonResource;
use App\Client\Resources\Api\V1\Catalog\ProductCardResource;

class WishlistItemResource extends JsonResource
{
    /**
     * @return array<string, mixed>
     */
    #[\Override]
    public function toArray(Request $request): array
    {
        /** @var WishlistItem $wishlistItem */
        $wishlistItem = $this->resource;

        return [
            'id' => $wishlistItem->id,
            'product' => new ProductCardResource($wishlistItem->product),
            'created_at' => $wishlistItem->created_at?->toISOString(),
        ];
    }
}
