<?php

declare(strict_types = 1);

namespace App\Client\Controllers\Api\V1\Profile;

use App\Models\User\User;
use Illuminate\Http\Request;
use App\Models\Catalog\Product;
use Illuminate\Http\JsonResponse;
use App\Http\Controllers\Controller;
use App\Models\Account\WishlistItem;
use App\Client\UseCases\Profile\AddWishlistItemUseCase;
use App\Client\UseCases\Profile\RemoveWishlistItemUseCase;
use App\Client\Resources\Api\V1\Profile\WishlistItemResource;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;
use App\Client\Requests\Api\V1\Profile\StoreWishlistItemRequest;

class WishlistController extends Controller
{
    public function index(Request $request): AnonymousResourceCollection
    {
        $items = WishlistItem::query()
            ->where('user_id', $this->user($request)->id)
            ->with(['product.brand', 'product.category', 'product.images', 'product.attributes'])
            ->latest()
            ->get();

        return WishlistItemResource::collection($items);
    }

    public function store(StoreWishlistItemRequest $request, AddWishlistItemUseCase $useCase): JsonResponse
    {
        $product = Product::query()->findOrFail($request->productId());
        $item = $useCase->execute($this->user($request), $product)
            ->load(['product.brand', 'product.category', 'product.images', 'product.attributes']);

        return new WishlistItemResource($item)->response()->setStatusCode(201);
    }

    public function destroy(Request $request, int $wishlistItem, RemoveWishlistItemUseCase $useCase): JsonResponse
    {
        $model = WishlistItem::query()->findOrFail($wishlistItem);
        $useCase->execute($this->user($request), $model);

        return response()->json(status: 204);
    }

    private function user(Request $request): User
    {
        $user = $request->user();
        abort_unless($user instanceof User, 401);

        return $user;
    }
}
