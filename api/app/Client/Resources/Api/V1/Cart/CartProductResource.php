<?php

declare(strict_types = 1);

namespace App\Client\Resources\Api\V1\Cart;

use Illuminate\Http\Request;
use App\Models\Catalog\Product;
use Illuminate\Http\Resources\Json\JsonResource;
use App\Client\Resources\Api\V1\Concerns\FormatsMoney;

class CartProductResource extends JsonResource
{
    use FormatsMoney;

    /**
     * @return array<string, mixed>
     */
    #[\Override]
    public function toArray(Request $request): array
    {
        /** @var Product $product */
        $product = $this->resource;
        $primaryImage = $product->relationLoaded('images')
            ? $product->images->firstWhere('is_primary', true) ?? $product->images->first()
            : null;

        return [
            'id' => $product->id,
            'slug' => $product->slug,
            'sku' => $product->sku,
            'name' => $product->name,
            'short_description' => $product->short_description,
            'price' => $this->money($product->price_cents),
            'stock_quantity' => $product->stock_quantity,
            'in_stock' => $product->stock_quantity > 0,
            'rating_average' => $product->rating_average,
            'reviews_count' => $product->reviews_count,
            'primary_image' => $primaryImage === null ? null : [
                'url' => $primaryImage->url,
                'alt' => $primaryImage->alt,
            ],
            'attributes' => $product->relationLoaded('attributes')
                ? $product->attributes->map(fn($attribute): array => [
                    'name' => $attribute->name,
                    'value' => $attribute->value,
                ])->values()->all()
                : [],
        ];
    }
}
