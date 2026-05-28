<?php

declare(strict_types = 1);

namespace App\Admin\Controllers\Api\V1\Review;

use Illuminate\Http\Response;
use App\Models\Catalog\Review;
use App\Models\Catalog\Product;
use App\Http\Controllers\Controller;
use Illuminate\Database\Eloquent\Builder;
use App\Admin\Resources\Api\V1\Review\ReviewResource;
use App\Admin\Requests\Api\V1\Review\ReviewIndexRequest;
use App\Admin\Requests\Api\V1\Review\StoreReviewRequest;
use App\Admin\Requests\Api\V1\Review\UpdateReviewRequest;
use App\Client\Services\Product\ProductReviewRatingService;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;

class ReviewController extends Controller
{
    public function __construct(private readonly ProductReviewRatingService $ratingService) {}

    public function index(ReviewIndexRequest $request): AnonymousResourceCollection
    {
        $query = Review::query()->with(['product.brand', 'product.category', 'user']);

        if ($request->filled('search')) {
            $search = mb_strtolower((string) $request->string('search'));
            $query->where(function (Builder $query) use ($search): void {
                $query->whereRaw('LOWER(body) LIKE ?', ['%' . $search . '%'])
                    ->orWhereRaw('LOWER(author_name) LIKE ?', ['%' . $search . '%'])
                    ->orWhereHas('product', fn(Builder $query): Builder => $query->whereRaw('LOWER(name) LIKE ?', ['%' . $search . '%']));
            });
        }

        if ($request->filled('status')) {
            $query->where('status', $request->input('status'));
        }

        if ($request->filled('product_id')) {
            $query->where('product_id', $request->integer('product_id'));
        }

        if ($request->filled('rating')) {
            $query->where('rating', $request->integer('rating'));
        }

        return ReviewResource::collection($query->latest()->paginate($request->perPage()));
    }

    public function store(StoreReviewRequest $request): ReviewResource
    {
        $review = Review::query()->create($request->validated());
        $this->refreshProductRating($review->product);

        return new ReviewResource($review->load(['product.brand', 'product.category', 'user']));
    }

    public function show(Review $review): ReviewResource
    {
        return new ReviewResource($review->load(['product.brand', 'product.category', 'user']));
    }

    public function update(UpdateReviewRequest $request, Review $review): ReviewResource
    {
        $originalProduct = $review->product;
        $review->fill($request->validated())->save();
        $review->refresh()->load('product');

        $this->refreshProductRating($originalProduct);
        if ($review->product_id !== $originalProduct->id) {
            $this->refreshProductRating($review->product);
        }

        return new ReviewResource($review->load(['product.brand', 'product.category', 'user']));
    }

    public function destroy(Review $review): Response
    {
        $product = $review->product;
        $review->delete();
        $this->refreshProductRating($product);

        return response()->noContent();
    }

    private function refreshProductRating(Product $product): void
    {
        $this->ratingService->refresh($product);
    }
}
