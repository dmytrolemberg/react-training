<?php

declare(strict_types = 1);

namespace App\Client\Controllers\Api\V1\Cart;

use App\Models\User\User;
use Illuminate\Http\Request;
use App\Models\Catalog\Product;
use App\Models\Commerce\CartItem;
use Illuminate\Http\JsonResponse;
use App\Http\Controllers\Controller;
use App\Client\Services\Cart\CartResolver;
use App\Client\UseCases\Cart\ClearCartUseCase;
use App\Client\UseCases\Cart\AddCartItemUseCase;
use App\Client\Resources\Api\V1\Cart\CartResource;
use App\Client\UseCases\Cart\RemoveCartItemUseCase;
use App\Client\Requests\Api\V1\Cart\AddCartItemRequest;
use App\Client\Requests\Api\V1\Cart\UpdateCartItemRequest;
use App\Client\UseCases\Cart\UpdateCartItemQuantityUseCase;

class CartController extends Controller
{
    public function show(Request $request, CartResolver $cartResolver): CartResource
    {
        $cart = $cartResolver->activeForUser($this->user($request))
            ->load(['items.product.brand', 'items.product.category', 'items.product.images', 'items.product.attributes']);

        return new CartResource($cart);
    }

    public function store(AddCartItemRequest $request, AddCartItemUseCase $useCase): CartResource
    {
        $product = Product::query()->findOrFail($request->productId());
        $cart = $useCase->execute($this->user($request), $product, $request->quantity());

        return new CartResource($cart);
    }

    public function update(UpdateCartItemRequest $request, int $item, UpdateCartItemQuantityUseCase $useCase): CartResource
    {
        $cartItem = CartItem::query()->findOrFail($item);
        $cart = $useCase->execute($this->user($request), $cartItem, $request->quantity());

        return new CartResource($cart);
    }

    public function destroy(Request $request, int $item, RemoveCartItemUseCase $useCase): CartResource
    {
        $cartItem = CartItem::query()->findOrFail($item);
        $cart = $useCase->execute($this->user($request), $cartItem);

        return new CartResource($cart);
    }

    public function clear(Request $request, ClearCartUseCase $useCase): JsonResponse
    {
        $cart = $useCase->execute($this->user($request));

        return new CartResource($cart)->response()->setStatusCode(200);
    }

    private function user(Request $request): User
    {
        $user = $request->user();
        abort_unless($user instanceof User, 401);

        return $user;
    }
}
