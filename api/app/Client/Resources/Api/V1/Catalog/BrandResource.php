<?php

declare(strict_types = 1);

namespace App\Client\Resources\Api\V1\Catalog;

use Illuminate\Http\Request;
use App\Models\Catalog\Brand;
use Illuminate\Http\Resources\Json\JsonResource;

class BrandResource extends JsonResource
{
    /**
     * @return array<string, mixed>
     */
    #[\Override]
    public function toArray(Request $request): array
    {
        /** @var Brand $brand */
        $brand = $this->resource;

        return [
            'id' => $brand->id,
            'slug' => $brand->slug,
            'name' => $brand->name,
            'description' => $brand->description,
            'logo_initial' => $brand->logo_initial,
            'products_count' => $brand->products_count ?? null,
        ];
    }
}
