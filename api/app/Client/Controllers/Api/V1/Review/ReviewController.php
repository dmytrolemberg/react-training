<?php

declare(strict_types = 1);

namespace App\Client\Controllers\Api\V1\Review;

use App\Models\User\User;
use Illuminate\Http\Request;
use App\Models\Catalog\Review;
use App\Models\Catalog\Product;
use Illuminate\Http\JsonResponse;
use App\Http\Controllers\Controller;
use App\Models\Catalog\ReviewStatus;
use App\Client\UseCases\Review\CreateReviewUseCase;
use App\Client\Resources\Api\V1\Review\ReviewResource;
use App\Client\Requests\Api\V1\Review\ReviewIndexRequest;
use App\Client\Requests\Api\V1\Review\StoreReviewRequest;
use App\Client\Resources\Api\V1\Review\ReviewSummaryResource;

class ReviewController extends Controller
{
    public function index(ReviewIndexRequest $request): JsonResponse
    {
        $query = Review::query()
            ->where('status', ReviewStatus::Approved->value)
            ->with(['product.brand', 'product.category', 'product.images', 'product.attributes'])
            ->latest();

        $rating = $request->integer('rating');
        if ($rating > 0) {
            $query->where('rating', $rating);
        }

        $productId = $request->integer('product_id');
        if ($productId > 0) {
            $query->where('product_id', $productId);
        }

        $reviews = $query->paginate($request->perPage());
        $summary = $this->summary($productId > 0 ? $productId : null);

        return response()->json([
            'data' => ReviewResource::collection($reviews)->resolve(),
            'summary' => new ReviewSummaryResource($summary)->resolve(),
            'links' => [
                'first' => $reviews->url(1),
                'last' => $reviews->url($reviews->lastPage()),
                'prev' => $reviews->previousPageUrl(),
                'next' => $reviews->nextPageUrl(),
            ],
            'meta' => [
                'current_page' => $reviews->currentPage(),
                'last_page' => $reviews->lastPage(),
                'per_page' => $reviews->perPage(),
                'total' => $reviews->total(),
            ],
        ]);
    }

    public function store(StoreReviewRequest $request, CreateReviewUseCase $useCase): JsonResponse
    {
        $product = Product::query()->findOrFail($request->productId());
        $review = $useCase->execute($this->user($request), $product, $request->payload());

        return new ReviewResource($review)->response()->setStatusCode(201);
    }

    /**
     * @return array<string, mixed>
     */
    private function summary(?int $productId): array
    {
        $query = Review::query()->where('status', ReviewStatus::Approved->value);
        if ($productId !== null) {
            $query->where('product_id', $productId);
        }

        $total = (int) $query->count();
        $average = $total > 0 ? round((float) $query->avg('rating'), 1) : 0.0;
        $breakdown = [];

        foreach ([5, 4, 3, 2, 1] as $rating) {
            $count = (int) (clone $query)->where('rating', $rating)->count();
            $breakdown[] = [
                'rating' => $rating,
                'count' => $count,
                'percentage' => $total > 0 ? round(($count / $total) * 100) : 0,
            ];
        }

        return [
            'average' => $average,
            'total' => $total,
            'breakdown' => $breakdown,
        ];
    }

    private function user(Request $request): User
    {
        $user = $request->user();
        abort_unless($user instanceof User, 401);

        return $user;
    }
}
