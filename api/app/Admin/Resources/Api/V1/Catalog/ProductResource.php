<?php

declare(strict_types = 1);

namespace App\Admin\Resources\Api\V1\Catalog;

use Illuminate\Http\Request;
use App\Models\Catalog\Product;
use Illuminate\Http\Resources\Json\JsonResource;
use App\Admin\Resources\Api\V1\Concerns\FormatsMoney;

class ProductResource extends JsonResource
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

        return [
            'id' => $product->id,
            'brand_id' => $product->brand_id,
            'category_id' => $product->category_id,
            'slug' => $product->slug,
            'sku' => $product->sku,
            'name' => $product->name,
            'short_description' => $product->short_description,
            'description_html' => $product->description_html,
            'price' => $this->money($product->price_cents),
            'price_cents' => $product->price_cents,
            'stock_quantity' => $product->stock_quantity,
            'is_active' => $product->is_active,
            'rating_average' => $product->rating_average,
            'reviews_count' => $product->reviews_count,
            'brand' => $product->relationLoaded('brand') ? new BrandResource($product->brand) : null,
            'category' => $product->relationLoaded('category') ? new CategoryResource($product->category) : null,
            'images' => $product->relationLoaded('images') ? ProductImageResource::collection($product->images) : [],
            'attributes' => $product->relationLoaded('attributes') ? ProductAttributeResource::collection($product->attributes) : [],
            'created_at' => $product->created_at?->toISOString(),
            'updated_at' => $product->updated_at?->toISOString(),
        ];
    }
}
