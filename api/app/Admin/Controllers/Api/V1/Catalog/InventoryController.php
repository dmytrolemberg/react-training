<?php

declare(strict_types = 1);

namespace App\Admin\Controllers\Api\V1\Catalog;

use App\Models\Catalog\Product;
use App\Http\Controllers\Controller;
use App\Admin\Resources\Api\V1\Catalog\ProductResource;
use App\Admin\Requests\Api\V1\Catalog\ProductIndexRequest;
use App\Admin\Requests\Api\V1\Catalog\UpdateInventoryRequest;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;

class InventoryController extends Controller
{
    public function index(ProductIndexRequest $request): AnonymousResourceCollection
    {
        $query = Product::query()->with(['brand', 'category', 'images', 'attributes']);

        if ($request->input('stock') === 'out_of_stock') {
            $query->where('stock_quantity', 0);
        } elseif ($request->input('stock') === 'in_stock') {
            $query->where('stock_quantity', '>', 0);
        }

        return ProductResource::collection($query->orderBy('stock_quantity')->paginate($request->perPage()));
    }

    public function show(Product $product): ProductResource
    {
        return new ProductResource($product->load(['brand', 'category', 'images', 'attributes']));
    }

    public function update(UpdateInventoryRequest $request, Product $product): ProductResource
    {
        $product->forceFill(['stock_quantity' => $request->integer('stock_quantity')])->save();

        return new ProductResource($product->refresh()->load(['brand', 'category', 'images', 'attributes']));
    }
}
