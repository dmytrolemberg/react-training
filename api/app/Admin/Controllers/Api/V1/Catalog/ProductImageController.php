<?php

declare(strict_types = 1);

namespace App\Admin\Controllers\Api\V1\Catalog;

use Illuminate\Http\Response;
use App\Models\Catalog\Product;
use App\Http\Controllers\Controller;
use App\Models\Catalog\ProductImage;
use App\Admin\Resources\Api\V1\Catalog\ProductImageResource;
use App\Admin\Requests\Api\V1\Catalog\StoreProductImageRequest;
use App\Admin\Requests\Api\V1\Catalog\UpdateProductImageRequest;

class ProductImageController extends Controller
{
    public function store(StoreProductImageRequest $request, Product $product): ProductImageResource
    {
        $image = $product->images()->create($request->validated());
        $this->normalizePrimary($image);

        return new ProductImageResource($image->refresh());
    }

    public function update(UpdateProductImageRequest $request, ProductImage $image): ProductImageResource
    {
        $image->fill($request->validated())->save();
        $this->normalizePrimary($image);

        return new ProductImageResource($image->refresh());
    }

    public function destroy(ProductImage $image): Response
    {
        $image->delete();

        return response()->noContent();
    }

    private function normalizePrimary(ProductImage $image): void
    {
        if (! $image->is_primary) {
            return;
        }

        ProductImage::query()
            ->where('product_id', $image->product_id)
            ->whereKeyNot($image->id)
            ->update(['is_primary' => false]);
    }
}
