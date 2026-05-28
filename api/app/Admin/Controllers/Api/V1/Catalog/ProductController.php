<?php

declare(strict_types = 1);

namespace App\Admin\Controllers\Api\V1\Catalog;

use Illuminate\Http\Response;
use App\Models\Catalog\Product;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use Illuminate\Database\Eloquent\Builder;
use App\Admin\Resources\Api\V1\Catalog\ProductResource;
use App\Admin\Requests\Api\V1\Catalog\ProductIndexRequest;
use App\Admin\Requests\Api\V1\Catalog\StoreProductRequest;
use App\Admin\Requests\Api\V1\Catalog\UpdateProductRequest;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;

class ProductController extends Controller
{
    public function index(ProductIndexRequest $request): AnonymousResourceCollection
    {
        $query = Product::query()->with(['brand', 'category', 'images', 'attributes']);
        $sort = $request->input('sort', 'created_desc');

        $this->applyFilters($query, $request);
        $this->applySort($query, is_string($sort) ? $sort : 'created_desc');

        return ProductResource::collection($query->paginate($request->perPage()));
    }

    public function store(StoreProductRequest $request): ProductResource
    {
        $product = DB::transaction(function () use ($request): Product {
            $data = $request->validated();
            $images = $this->payloadList($data['images'] ?? []);
            $attributes = $this->payloadList($data['attributes'] ?? []);
            unset($data['images'], $data['attributes']);

            $product = Product::query()->create($data);
            $this->syncImages($product, $images);
            $this->syncAttributes($product, $attributes);

            return $product;
        });

        return new ProductResource($product->load(['brand', 'category', 'images', 'attributes']));
    }

    public function show(Product $product): ProductResource
    {
        return new ProductResource($product->load(['brand', 'category', 'images', 'attributes']));
    }

    public function update(UpdateProductRequest $request, Product $product): ProductResource
    {
        DB::transaction(function () use ($request, $product): void {
            $data = $request->validated();
            $images = $data['images'] ?? null;
            $attributes = $data['attributes'] ?? null;
            unset($data['images'], $data['attributes']);

            $product->fill($data)->save();

            if (is_array($images)) {
                $this->syncImages($product, $this->payloadList($images));
            }

            if (is_array($attributes)) {
                $this->syncAttributes($product, $this->payloadList($attributes));
            }
        });

        return new ProductResource($product->refresh()->load(['brand', 'category', 'images', 'attributes']));
    }

    public function destroy(Product $product): Response
    {
        $product->delete();

        return response()->noContent();
    }

    /**
     * @param Builder<Product> $query
     */
    private function applyFilters(Builder $query, ProductIndexRequest $request): void
    {
        if ($request->filled('search')) {
            $search = mb_strtolower((string) $request->string('search'));
            $query->where(function (Builder $query) use ($search): void {
                $query->whereRaw('LOWER(name) LIKE ?', ['%' . $search . '%'])
                    ->orWhereRaw('LOWER(sku) LIKE ?', ['%' . $search . '%'])
                    ->orWhereRaw('LOWER(slug) LIKE ?', ['%' . $search . '%']);
            });
        }

        if ($request->filled('brand_id')) {
            $query->where('brand_id', $request->integer('brand_id'));
        }

        if ($request->filled('category_id')) {
            $query->where('category_id', $request->integer('category_id'));
        }

        match ($request->input('status')) {
            'active' => $query->where('is_active', true),
            'inactive' => $query->where('is_active', false),
            default => null,
        };

        match ($request->input('stock')) {
            'in_stock' => $query->where('stock_quantity', '>', 0),
            'out_of_stock' => $query->where('stock_quantity', 0),
            default => null,
        };
    }

    /**
     * @param Builder<Product> $query
     */
    private function applySort(Builder $query, string $sort): void
    {
        match ($sort) {
            'name' => $query->orderBy('name'),
            'price_asc' => $query->orderBy('price_cents'),
            'price_desc' => $query->orderByDesc('price_cents'),
            'stock_asc' => $query->orderBy('stock_quantity'),
            'stock_desc' => $query->orderByDesc('stock_quantity'),
            default => $query->latest(),
        };
    }

    /**
     * @param array<int, array<string, mixed>> $images
     */
    private function syncImages(Product $product, array $images): void
    {
        $product->images()->delete();

        foreach (array_values($images) as $position => $image) {
            $product->images()->create([
                'url' => $image['url'],
                'alt' => $image['alt'] ?? null,
                'position' => $image['position'] ?? $position + 1,
                'is_primary' => $image['is_primary'] ?? $position === 0,
            ]);
        }

        $this->normalizePrimaryImage($product);
    }

    /**
     * @param array<int, array<string, mixed>> $attributes
     */
    private function syncAttributes(Product $product, array $attributes): void
    {
        $product->attributes()->delete();

        foreach (array_values($attributes) as $position => $attribute) {
            $product->attributes()->create([
                'name' => $attribute['name'],
                'value' => $attribute['value'],
                'position' => $attribute['position'] ?? $position + 1,
            ]);
        }
    }

    private function normalizePrimaryImage(Product $product): void
    {
        $images = $product->images()->orderByDesc('is_primary')->orderBy('position')->get();
        $primary = $images->first();

        if ($primary === null) {
            return;
        }

        $product->images()->whereKeyNot($primary->id)->update(['is_primary' => false]);
        $primary->forceFill(['is_primary' => true])->save();
    }

    /**
     * @return array<int, array<string, mixed>>
     */
    private function payloadList(mixed $items): array
    {
        if (! is_array($items)) {
            return [];
        }

        $payloads = [];

        foreach ($items as $item) {
            if (! is_array($item)) {
                continue;
            }

            $payload = [];
            foreach ($item as $key => $value) {
                if (is_string($key)) {
                    $payload[$key] = $value;
                }
            }

            $payloads[] = $payload;
        }

        return $payloads;
    }
}
