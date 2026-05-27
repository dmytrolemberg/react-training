<?php

declare(strict_types = 1);

namespace App\Client\Controllers\Api\V1\Catalog;

use App\Models\Catalog\Brand;
use App\Models\Catalog\Product;
use App\Models\Catalog\Category;
use Illuminate\Http\JsonResponse;
use App\Http\Controllers\Controller;
use Illuminate\Database\Eloquent\Builder;
use App\Client\Resources\Api\V1\Catalog\BrandResource;
use App\Client\Resources\Api\V1\Catalog\CategoryResource;
use App\Client\Requests\Api\V1\Catalog\ProductIndexRequest;
use App\Client\Resources\Api\V1\Catalog\ProductCardResource;
use App\Client\Resources\Api\V1\Catalog\ProductDetailResource;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;

class CatalogController extends Controller
{
    public function home(): JsonResponse
    {
        $categories = Category::query()
            ->where('is_active', true)
            ->withCount(['products' => fn(Builder $query): Builder => $query->where('is_active', true)])
            ->orderBy('position')
            ->limit(4)
            ->get();

        $brands = Brand::query()
            ->where('is_active', true)
            ->withCount(['products' => fn(Builder $query): Builder => $query->where('is_active', true)])
            ->orderBy('name')
            ->limit(6)
            ->get();

        $featuredProducts = Product::query()
            ->where('is_active', true)
            ->where('stock_quantity', '>', 0)
            ->with(['brand', 'category', 'images', 'attributes'])
            ->orderByDesc('rating_average')
            ->limit(3)
            ->get();

        return response()->json([
            'data' => [
                'categories' => CategoryResource::collection($categories),
                'brands' => BrandResource::collection($brands),
                'featured_products' => ProductCardResource::collection($featuredProducts),
            ],
        ]);
    }

    public function products(ProductIndexRequest $request): AnonymousResourceCollection
    {
        $query = Product::query()
            ->where('is_active', true)
            ->with(['brand', 'category', 'images', 'attributes']);

        if ($request->filled('search')) {
            $search = mb_strtolower((string) $request->string('search'));
            $query->where(function (Builder $query) use ($search): void {
                $query->whereRaw('LOWER(name) LIKE ?', ['%' . $search . '%'])
                    ->orWhereRaw('LOWER(short_description) LIKE ?', ['%' . $search . '%'])
                    ->orWhereHas('brand', fn(Builder $query): Builder => $query->whereRaw('LOWER(name) LIKE ?', ['%' . $search . '%']))
                    ->orWhereHas('category', fn(Builder $query): Builder => $query->whereRaw('LOWER(name) LIKE ?', ['%' . $search . '%']))
                    ->orWhereHas('attributes', fn(Builder $query): Builder => $query->whereRaw('LOWER(name) LIKE ?', ['%' . $search . '%'])->orWhereRaw('LOWER(value) LIKE ?', ['%' . $search . '%']));
            });
        }

        $categories = $request->input('categories', []);
        if (is_array($categories) && $categories !== []) {
            $query->whereHas('category', fn(Builder $query): Builder => $query->whereIn('slug', $categories));
        }

        $brands = $request->input('brands', []);
        if (is_array($brands) && $brands !== []) {
            $query->whereHas('brand', fn(Builder $query): Builder => $query->whereIn('slug', $brands));
        }

        if ($request->filled('min_rating')) {
            $minRating = $request->input('min_rating');
            if (is_numeric($minRating)) {
                $query->where('rating_average', '>=', (float) $minRating);
            }
        }

        if ($request->boolean('in_stock')) {
            $query->where('stock_quantity', '>', 0);
        }

        match ($request->sort()) {
            'price_asc' => $query->orderBy('price_cents'),
            'price_desc' => $query->orderByDesc('price_cents'),
            'rating_desc' => $query->orderByDesc('rating_average')->orderByDesc('reviews_count'),
            default => $query->orderByDesc('rating_average')->orderBy('name'),
        };

        return ProductCardResource::collection($query->paginate($request->perPage()));
    }

    public function product(string $slug): JsonResponse
    {
        $product = Product::query()
            ->where('slug', $slug)
            ->where('is_active', true)
            ->with(['brand', 'category', 'images', 'attributes'])
            ->firstOrFail();

        $related = Product::query()
            ->where('is_active', true)
            ->where('category_id', $product->category_id)
            ->whereKeyNot($product->id)
            ->with(['brand', 'category', 'images', 'attributes'])
            ->orderByDesc('rating_average')
            ->limit(3)
            ->get();

        return response()->json([
            'data' => new ProductDetailResource($product)->resolve(),
            'related' => ProductCardResource::collection($related)->resolve(),
        ]);
    }

    public function brands(): AnonymousResourceCollection
    {
        $brands = Brand::query()
            ->where('is_active', true)
            ->withCount(['products' => fn(Builder $query): Builder => $query->where('is_active', true)])
            ->orderBy('name')
            ->get();

        return BrandResource::collection($brands);
    }

    public function categories(): AnonymousResourceCollection
    {
        $categories = Category::query()
            ->where('is_active', true)
            ->withCount(['products' => fn(Builder $query): Builder => $query->where('is_active', true)])
            ->orderBy('position')
            ->get();

        return CategoryResource::collection($categories);
    }
}
