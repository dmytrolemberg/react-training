<?php

declare(strict_types = 1);

namespace App\Client\Resources\Api\V1\Catalog;

use Illuminate\Http\Request;
use App\Models\Catalog\Product;

class ProductDetailResource extends ProductCardResource
{
    /**
     * @return array<string, mixed>
     */
    #[\Override]
    public function toArray(Request $request): array
    {
        /** @var Product $product */
        $product = $this->resource;
        $data = parent::toArray($request);

        $data['description_html'] = $product->description_html;
        $data['images'] = $product->relationLoaded('images')
            ? $product->images->map(fn($image): array => [
                'url' => $image->url,
                'alt' => $image->alt,
                'position' => $image->position,
                'is_primary' => $image->is_primary,
            ])->values()->all()
            : [];

        return $data;
    }
}
