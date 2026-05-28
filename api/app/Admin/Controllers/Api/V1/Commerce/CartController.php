<?php

declare(strict_types = 1);

namespace App\Admin\Controllers\Api\V1\Commerce;

use App\Models\Commerce\Cart;
use Illuminate\Http\Response;
use App\Http\Controllers\Controller;
use Illuminate\Database\Eloquent\Builder;
use App\Admin\Resources\Api\V1\Commerce\CartResource;
use App\Admin\Requests\Api\V1\Commerce\CartIndexRequest;
use App\Admin\Requests\Api\V1\Commerce\StoreCartRequest;
use App\Admin\Requests\Api\V1\Commerce\UpdateCartRequest;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;

class CartController extends Controller
{
    public function index(CartIndexRequest $request): AnonymousResourceCollection
    {
        $query = Cart::query()->with(['user', 'items.product']);

        if ($request->filled('search')) {
            $search = mb_strtolower((string) $request->string('search'));
            $query->whereHas('user', fn(Builder $query): Builder => $query
                ->whereRaw('LOWER(email) LIKE ?', ['%' . $search . '%'])
                ->orWhereRaw('LOWER(first_name) LIKE ?', ['%' . $search . '%'])
                ->orWhereRaw('LOWER(last_name) LIKE ?', ['%' . $search . '%']));
        }

        return CartResource::collection($query->latest()->paginate($request->perPage()));
    }

    public function store(StoreCartRequest $request): CartResource
    {
        $cart = Cart::query()->create($request->validated());

        return new CartResource($cart->load(['user', 'items.product']));
    }

    public function show(Cart $cart): CartResource
    {
        return new CartResource($cart->load(['user', 'items.product']));
    }

    public function update(UpdateCartRequest $request, Cart $cart): CartResource
    {
        $cart->fill($request->validated())->save();

        return new CartResource($cart->refresh()->load(['user', 'items.product']));
    }

    public function destroy(Cart $cart): Response
    {
        $cart->delete();

        return response()->noContent();
    }
}
