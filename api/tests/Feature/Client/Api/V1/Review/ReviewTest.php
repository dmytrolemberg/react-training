<?php

declare(strict_types = 1);

namespace Tests\Feature\Client\Api\V1\Review;

use Tests\TestCase;
use App\Models\User\User;
use App\Models\Catalog\Review;
use App\Models\Commerce\Order;
use App\Models\Catalog\Product;
use App\Models\Commerce\OrderStatus;

class ReviewTest extends TestCase
{
    public function testPublicReviewsCanBeListedWithSummaryAndFilter(): void
    {
        $this->getJson('/api/v1/reviews?rating=5')
            ->assertOk()
            ->assertJsonPath('summary.total', 3)
            ->assertJsonPath('data.0.rating', 5)
            ->assertJsonMissing(['author_name' => 'Hidden']);
    }

    public function testPublicReviewsCanBeFilteredByProduct(): void
    {
        $product = Product::query()->where('slug', 'everyday-carry-pack')->firstOrFail();

        $this->getJson('/api/v1/reviews?product_id=' . $product->id)
            ->assertOk()
            ->assertJsonPath('summary.total', 1)
            ->assertJsonPath('data.0.product.slug', 'everyday-carry-pack');
    }

    public function testReviewIndexValidationRejectsInvalidFilters(): void
    {
        $this->getJson('/api/v1/reviews?rating=6&product_id=999999&per_page=100')
            ->assertUnprocessable()
            ->assertJsonValidationErrors(['rating', 'product_id', 'per_page']);
    }

    public function testReviewCreationRequiresAuthentication(): void
    {
        $product = Product::query()->where('slug', 'travel-tech-pouch')->firstOrFail();

        $this->postJson('/api/v1/reviews', [
            'product_id' => $product->id,
            'rating' => 5,
            'body' => 'Great pouch.',
        ])->assertUnauthorized();
    }

    public function testReviewCreationValidatesPayload(): void
    {
        $this->actingAs($this->user())
            ->postJson('/api/v1/reviews', [
                'product_id' => 999999,
                'rating' => 8,
                'body' => 'bad',
            ])
            ->assertUnprocessable()
            ->assertJsonValidationErrors(['product_id', 'rating', 'body']);
    }

    public function testAuthenticatedUserCanCreateReviewAndAggregateIsUpdated(): void
    {
        $product = Product::query()->where('slug', 'travel-tech-pouch')->firstOrFail();
        $previousCount = $product->reviews_count;

        $this->actingAs($this->user())
            ->postJson('/api/v1/reviews', [
                'product_id' => $product->id,
                'rating' => 5,
                'body' => 'Excellent organizer for a compact kit.',
            ])
            ->assertCreated()
            ->assertJsonPath('data.rating', 5)
            ->assertJsonPath('data.is_verified_purchase', false);

        $this->assertSame($previousCount + 1, $product->refresh()->reviews_count);
    }

    public function testInactiveProductCannotBeReviewed(): void
    {
        $product = Product::query()->where('slug', 'hidden-archive-jacket')->firstOrFail();

        $this->actingAs($this->user())
            ->postJson('/api/v1/reviews', [
                'product_id' => $product->id,
                'rating' => 4,
                'body' => 'Inactive product should not accept a review.',
            ])
            ->assertUnprocessable()
            ->assertJsonValidationErrors(['product_id']);
    }

    public function testReviewWithNonDeliveredOrderItemIsRejected(): void
    {
        $order = Order::query()->where('number', '1048')->firstOrFail();
        $orderItem = $order->items()->firstOrFail();

        $this->actingAs($this->user())
            ->postJson('/api/v1/reviews', [
                'product_id' => $orderItem->product_id,
                'order_item_id' => $orderItem->id,
                'rating' => 5,
                'body' => 'This should require delivered order.',
            ])
            ->assertUnprocessable()
            ->assertJsonValidationErrors(['order_item_id']);
    }

    public function testReviewOrderItemMustBelongToTheReviewedProduct(): void
    {
        $order = Order::query()->where('number', '1048')->firstOrFail();
        $order->forceFill(['status' => OrderStatus::Delivered->value])->save();
        $orderItem = $order->items()->where('product_slug', 'everyday-carry-pack')->firstOrFail();
        $product = Product::query()->where('slug', 'travel-tech-pouch')->firstOrFail();

        $this->actingAs($this->user())
            ->postJson('/api/v1/reviews', [
                'product_id' => $product->id,
                'order_item_id' => $orderItem->id,
                'rating' => 5,
                'body' => 'Mismatched order item should be rejected.',
            ])
            ->assertUnprocessable()
            ->assertJsonValidationErrors(['order_item_id']);
    }

    public function testReviewWithDeliveredOrderItemIsVerified(): void
    {
        $order = Order::query()->where('number', '1048')->firstOrFail();
        $order->forceFill(['status' => OrderStatus::Delivered->value])->save();
        $orderItem = $order->items()->firstOrFail();

        $this->actingAs($this->user())
            ->postJson('/api/v1/reviews', [
                'product_id' => $orderItem->product_id,
                'order_item_id' => $orderItem->id,
                'rating' => 5,
                'body' => 'Verified review after delivery.',
            ])
            ->assertCreated()
            ->assertJsonPath('data.is_verified_purchase', true);
    }

    public function testPendingReviewsAreHiddenFromPublicList(): void
    {
        $pending = Review::query()->where('status', 'pending')->firstOrFail();

        $this->getJson('/api/v1/reviews')
            ->assertOk()
            ->assertJsonMissing(['id' => $pending->id]);
    }

    private function user(): User
    {
        return User::query()->where('email', 'user@example.com')->firstOrFail();
    }
}
