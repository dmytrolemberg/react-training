<?php

declare(strict_types = 1);

namespace Tests\Feature\Admin\Api\V1\Review;

use App\Models\Catalog\Review;
use App\Models\Catalog\Product;
use App\Models\Catalog\ReviewStatus;
use Tests\Feature\Admin\Api\V1\AdminApiTestCase;

class ReviewManagementTest extends AdminApiTestCase
{
    public function testAdminCanManageReviewsAndRefreshProductRatings(): void
    {
        $product = Product::query()->where('slug', 'travel-tech-pouch')->firstOrFail();

        $review = $this->actingAsAdmin()
            ->postJson('/admin/api/v1/reviews', [
                'product_id' => $product->id,
                'user_id' => $this->user()->id,
                'rating' => 5,
                'body' => 'Admin-created approved review.',
                'author_name' => 'Admin Tester',
                'status' => ReviewStatus::Approved->value,
                'is_verified_purchase' => true,
            ])
            ->assertCreated()
            ->assertJsonPath('data.status', 'approved');

        $this->actingAsAdmin()
            ->getJson('/admin/api/v1/reviews?status=approved')
            ->assertOk()
            ->assertJsonFragment(['author_name' => 'Admin Tester']);

        $this->actingAsAdmin()
            ->patchJson('/admin/api/v1/reviews/' . $review->json('data.id'), [
                'status' => ReviewStatus::Rejected->value,
                'body' => 'Rejected after moderation.',
            ])
            ->assertOk()
            ->assertJsonPath('data.status', 'rejected');

        $this->assertSame(0, Review::query()->whereKey($review->json('data.id'))->where('status', ReviewStatus::Approved->value)->count());

        $this->actingAsAdmin()
            ->deleteJson('/admin/api/v1/reviews/' . $review->json('data.id'))
            ->assertNoContent();
    }

    public function testReviewValidationRejectsInvalidRating(): void
    {
        $product = Product::query()->firstOrFail();

        $this->actingAsAdmin()
            ->postJson('/admin/api/v1/reviews', [
                'product_id' => $product->id,
                'rating' => 9,
                'body' => 'Bad',
                'status' => ReviewStatus::Approved->value,
            ])
            ->assertUnprocessable()
            ->assertJsonValidationErrors(['rating', 'body']);
    }
}
