<?php

declare(strict_types = 1);

namespace App\Admin\Controllers\Api\V1\Catalog;

use Illuminate\Http\Request;
use App\Models\Catalog\Brand;
use Illuminate\Http\Response;
use App\Http\Controllers\Controller;
use Illuminate\Database\Eloquent\Builder;
use App\Admin\Resources\Api\V1\Catalog\BrandResource;
use App\Admin\Requests\Api\V1\Catalog\StoreBrandRequest;
use App\Admin\Requests\Api\V1\Catalog\UpdateBrandRequest;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;

class BrandController extends Controller
{
    public function index(Request $request): AnonymousResourceCollection
    {
        $query = Brand::query()->withCount('products');

        if ($request->filled('search')) {
            $search = mb_strtolower((string) $request->string('search'));
            $query->where(fn(Builder $query): Builder => $query
                ->whereRaw('LOWER(name) LIKE ?', ['%' . $search . '%'])
                ->orWhereRaw('LOWER(slug) LIKE ?', ['%' . $search . '%']));
        }

        return BrandResource::collection($query->orderBy('name')->paginate((int) $request->integer('per_page', 15)));
    }

    public function store(StoreBrandRequest $request): BrandResource
    {
        $brand = Brand::query()->create($request->validated());

        return new BrandResource($brand->loadCount('products'));
    }

    public function show(Brand $brand): BrandResource
    {
        return new BrandResource($brand->loadCount('products'));
    }

    public function update(UpdateBrandRequest $request, Brand $brand): BrandResource
    {
        $brand->fill($request->validated())->save();

        return new BrandResource($brand->refresh()->loadCount('products'));
    }

    public function destroy(Brand $brand): Response
    {
        $brand->delete();

        return response()->noContent();
    }
}
