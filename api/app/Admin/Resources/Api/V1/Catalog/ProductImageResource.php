<?php

declare(strict_types = 1);

namespace App\Admin\Resources\Api\V1\Catalog;

use Illuminate\Http\Request;
use App\Models\Catalog\ProductImage;
use Illuminate\Http\Resources\Json\JsonResource;

class ProductImageResource extends JsonResource
{
    /**
     * @return array<string, mixed>
     */
    #[\Override]
    public function toArray(Request $request): array
    {
        /** @var ProductImage $image */
        $image = $this->resource;

        return [
            'id' => $image->id,
            'product_id' => $image->product_id,
            'url' => $image->url,
            'alt' => $image->alt,
            'position' => $image->position,
            'is_primary' => $image->is_primary,
            'created_at' => $image->created_at?->toISOString(),
            'updated_at' => $image->updated_at?->toISOString(),
        ];
    }
}
