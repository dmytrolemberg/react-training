<?php

declare(strict_types = 1);

namespace App\Admin\Resources\Api\V1\Review;

use Illuminate\Http\Request;
use App\Models\Catalog\Review;
use Illuminate\Http\Resources\Json\JsonResource;
use App\Admin\Resources\Api\V1\Catalog\ProductResource;
use App\Admin\Resources\Api\V1\Customer\CustomerResource;

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
            'product_id' => $review->product_id,
            'user_id' => $review->user_id,
            'order_item_id' => $review->order_item_id,
            'rating' => $review->rating,
            'body' => $review->body,
            'author_name' => $review->author_name,
            'status' => $review->status->value,
            'is_verified_purchase' => $review->is_verified_purchase,
            'product' => $review->relationLoaded('product') ? new ProductResource($review->product) : null,
            'user' => $review->relationLoaded('user') && $review->user !== null ? new CustomerResource($review->user) : null,
            'created_at' => $review->created_at?->toISOString(),
            'updated_at' => $review->updated_at?->toISOString(),
        ];
    }
}
