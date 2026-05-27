<?php

declare(strict_types = 1);

namespace App\Client\Resources\Api\V1\Review;

use Illuminate\Http\Request;
use App\Models\Catalog\Review;
use Illuminate\Http\Resources\Json\JsonResource;
use App\Client\Resources\Api\V1\Catalog\ProductCardResource;

class ReviewResource extends JsonResource
{
    /**
     * @return array<string, mixed>
     */
    #[\Override]
    public function toArray(Request $request): array
    {
        /** @var Review $review */
        $review = $this->resource;

        return [
            'id' => $review->id,
            'rating' => $review->rating,
            'body' => $review->body,
            'author_name' => $review->author_name,
            'is_verified_purchase' => $review->is_verified_purchase,
            'created_at' => $review->created_at?->toISOString(),
            'product' => $review->relationLoaded('product') ? new ProductCardResource($review->product) : null,
        ];
    }
}
