<?php

declare(strict_types = 1);

namespace App\Admin\Controllers\Api\V1\Catalog;

use Illuminate\Http\Request;
use Illuminate\Http\Response;
use App\Models\Catalog\Category;
use App\Http\Controllers\Controller;
use Illuminate\Database\Eloquent\Builder;
use App\Admin\Resources\Api\V1\Catalog\CategoryResource;
use App\Admin\Requests\Api\V1\Catalog\StoreCategoryRequest;
use App\Admin\Requests\Api\V1\Catalog\UpdateCategoryRequest;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;

class CategoryController extends Controller
{
    public function index(Request $request): AnonymousResourceCollection
    {
        $query = Category::query()->withCount('products');

        if ($request->filled('search')) {
            $search = mb_strtolower((string) $request->string('search'));
            $query->where(fn(Builder $query): Builder => $query
                ->whereRaw('LOWER(name) LIKE ?', ['%' . $search . '%'])
                ->orWhereRaw('LOWER(slug) LIKE ?', ['%' . $search . '%']));
        }

        return CategoryResource::collection($query->orderBy('position')->paginate((int) $request->integer('per_page', 15)));
    }

    public function store(StoreCategoryRequest $request): CategoryResource
    {
        $category = Category::query()->create($request->validated());

        return new CategoryResource($category->loadCount('products'));
    }

    public function show(Category $category): CategoryResource
    {
        return new CategoryResource($category->loadCount('products'));
    }

    public function update(UpdateCategoryRequest $request, Category $category): CategoryResource
    {
        $category->fill($request->validated())->save();

        return new CategoryResource($category->refresh()->loadCount('products'));
    }

    public function destroy(Category $category): Response
    {
        $category->delete();

        return response()->noContent();
    }
}
