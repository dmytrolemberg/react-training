<?php

declare(strict_types = 1);

namespace App\Client\Services\Product;

use App\Models\Catalog\Product;
use App\Models\Catalog\ReviewStatus;

class ProductReviewRatingService
{
    public function includeApprovedRating(Product $product, int $rating): void
    {
        $currentCount = $product->reviews_count;
        $newCount = $currentCount + 1;
        $newAverage = (($product->rating_average * $currentCount) + $rating) / $newCount;

        $product->forceFill([
            'rating_average' => round($newAverage, 1),
            'reviews_count' => $newCount,
        ])->save();
    }

    public function refresh(Product $product): void
    {
        $approvedReviews = $product->reviews()
            ->where('status', ReviewStatus::Approved->value);

        $count = (int) $approvedReviews->count();
        $average = $count > 0
            ? round((float) $approvedReviews->avg('rating'), 1)
            : 0.0;

        $product->forceFill([
            'rating_average' => $average,
            'reviews_count' => $count,
        ])->save();
    }
}
