<?php

declare(strict_types = 1);

namespace App\Admin\Resources\Api\V1\Catalog;

use Illuminate\Http\Request;
use App\Models\Catalog\ProductAttribute;
use Illuminate\Http\Resources\Json\JsonResource;

class ProductAttributeResource extends JsonResource
{
    /**
     * @return array<string, mixed>
     */
    #[\Override]
    public function toArray(Request $request): array
    {
        /** @var ProductAttribute $attribute */
        $attribute = $this->resource;

        return [
            'id' => $attribute->id,
            'product_id' => $attribute->product_id,
            'name' => $attribute->name,
            'value' => $attribute->value,
            'position' => $attribute->position,
            'created_at' => $attribute->created_at?->toISOString(),
            'updated_at' => $attribute->updated_at?->toISOString(),
        ];
    }
}
