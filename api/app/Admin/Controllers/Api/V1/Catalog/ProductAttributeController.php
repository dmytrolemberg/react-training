<?php

declare(strict_types = 1);

namespace App\Admin\Controllers\Api\V1\Catalog;

use Illuminate\Http\Response;
use App\Models\Catalog\Product;
use App\Http\Controllers\Controller;
use App\Models\Catalog\ProductAttribute;
use App\Admin\Resources\Api\V1\Catalog\ProductAttributeResource;
use App\Admin\Requests\Api\V1\Catalog\StoreProductAttributeRequest;
use App\Admin\Requests\Api\V1\Catalog\UpdateProductAttributeRequest;

class ProductAttributeController extends Controller
{
    public function store(StoreProductAttributeRequest $request, Product $product): ProductAttributeResource
    {
        $attribute = $product->attributes()->create($request->validated());

        return new ProductAttributeResource($attribute);
    }

    public function update(UpdateProductAttributeRequest $request, ProductAttribute $attribute): ProductAttributeResource
    {
        $attribute->fill($request->validated())->save();

        return new ProductAttributeResource($attribute->refresh());
    }

    public function destroy(ProductAttribute $attribute): Response
    {
        $attribute->delete();

        return response()->noContent();
    }
}
