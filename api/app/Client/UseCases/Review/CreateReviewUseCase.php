<?php

declare(strict_types = 1);

namespace App\Client\UseCases\Review;

use App\Models\User\User;
use App\Models\Catalog\Review;
use App\Models\Catalog\Product;
use App\Models\Commerce\OrderItem;
use App\Models\Catalog\ReviewStatus;
use App\Models\Commerce\OrderStatus;
use Illuminate\Validation\ValidationException;
use App\Client\Services\Product\ProductReviewRatingService;

class CreateReviewUseCase
{
    public function __construct(
        private readonly ProductReviewRatingService $ratingService,
    ) {}

    /**
     * @param array{product_id: int, rating: int, body: string, order_item_id?: int|null} $data
     * @throws ValidationException
     */
    public function execute(User $user, Product $product, array $data): Review
    {
        if (!$product->is_active) {
            throw ValidationException::withMessages(['product_id' => ['This product is not available for review.']]);
        }

        $orderItem = $this->resolveOrderItem($user, $product, $data['order_item_id'] ?? null);

        $review = Review::query()->create([
            'product_id' => $product->id,
            'user_id' => $user->id,
            'order_item_id' => $orderItem?->id,
            'rating' => $data['rating'],
            'body' => $data['body'],
            'author_name' => $user->name,
            'status' => ReviewStatus::Approved->value,
            'is_verified_purchase' => $orderItem instanceof OrderItem,
        ]);

        $this->ratingService->includeApprovedRating($product, $review->rating);

        return $review->refresh()->load(['product.brand', 'product.category', 'user']);
    }

    /**
     * @throws ValidationException
     */
    private function resolveOrderItem(User $user, Product $product, ?int $orderItemId): ?OrderItem
    {
        if ($orderItemId === null) {
            return null;
        }

        $orderItem = OrderItem::query()
            ->whereKey($orderItemId)
            ->where('product_id', $product->id)
            ->whereHas('order', fn($query) => $query
                ->where('user_id', $user->id)
                ->where('status', OrderStatus::Delivered->value))
            ->first();

        if (!$orderItem instanceof OrderItem) {
            throw ValidationException::withMessages(['order_item_id' => ['Select a delivered order item that belongs to this product.']]);
        }

        return $orderItem;
    }
}
