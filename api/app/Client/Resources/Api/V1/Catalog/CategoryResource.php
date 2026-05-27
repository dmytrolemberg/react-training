<?php

declare(strict_types = 1);

namespace App\Client\Resources\Api\V1\Catalog;

use Illuminate\Http\Request;
use App\Models\Catalog\Category;
use Illuminate\Http\Resources\Json\JsonResource;

class CategoryResource extends JsonResource
{
    /**
     * @return array<string, mixed>
     */
    #[\Override]
    public function toArray(Request $request): array
    {
        /** @var Category $category */
        $category = $this->resource;

        return [
            'id' => $category->id,
            'slug' => $category->slug,
            'name' => $category->name,
            'description' => $category->description,
            'position' => $category->position,
            'products_count' => $category->products_count ?? null,
        ];
    }
}
