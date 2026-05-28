<?php

declare(strict_types = 1);

namespace App\Admin\Requests\Api\V1\Catalog;

use App\Models\Catalog\Product;
use Illuminate\Validation\Rule;
use Illuminate\Foundation\Http\FormRequest;

class UpdateProductRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    /**
     * @return array<string, mixed>
     */
    public function rules(): array
    {
        $product = $this->route('product');
        $productId = $product instanceof Product ? $product->id : null;

        return [
            'brand_id' => ['sometimes', 'integer', Rule::exists('brands', 'id')],
            'category_id' => ['sometimes', 'integer', Rule::exists('categories', 'id')],
            'slug' => ['sometimes', 'string', 'max:255', Rule::unique('products', 'slug')->ignore($productId)],
            'sku' => ['sometimes', 'string', 'max:255', Rule::unique('products', 'sku')->ignore($productId)],
            'name' => ['sometimes', 'string', 'max:255'],
            'short_description' => ['sometimes', 'string', 'max:1000'],
            'description_html' => ['sometimes', 'string'],
            'price_cents' => ['sometimes', 'integer', 'min:0'],
            'stock_quantity' => ['sometimes', 'integer', 'min:0'],
            'is_active' => ['sometimes', 'boolean'],
            'images' => ['sometimes', 'array'],
            'images.*.url' => ['required_with:images', 'url', 'max:2048'],
            'images.*.alt' => ['nullable', 'string', 'max:255'],
            'images.*.position' => ['nullable', 'integer', 'min:0'],
            'images.*.is_primary' => ['nullable', 'boolean'],
            'attributes' => ['sometimes', 'array'],
            'attributes.*.name' => ['required_with:attributes', 'string', 'max:120'],
            'attributes.*.value' => ['required_with:attributes', 'string', 'max:255'],
            'attributes.*.position' => ['nullable', 'integer', 'min:0'],
        ];
    }
}
