<?php

declare(strict_types = 1);

namespace App\Admin\Requests\Api\V1\Catalog;

use Illuminate\Validation\Rule;
use Illuminate\Foundation\Http\FormRequest;

class StoreProductRequest extends FormRequest
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
        return [
            'brand_id' => ['required', 'integer', Rule::exists('brands', 'id')],
            'category_id' => ['required', 'integer', Rule::exists('categories', 'id')],
            'slug' => ['required', 'string', 'max:255', Rule::unique('products', 'slug')],
            'sku' => ['required', 'string', 'max:255', Rule::unique('products', 'sku')],
            'name' => ['required', 'string', 'max:255'],
            'short_description' => ['required', 'string', 'max:1000'],
            'description_html' => ['required', 'string'],
            'price_cents' => ['required', 'integer', 'min:0'],
            'stock_quantity' => ['required', 'integer', 'min:0'],
            'is_active' => ['required', 'boolean'],
            'images' => ['nullable', 'array'],
            'images.*.url' => ['required_with:images', 'url', 'max:2048'],
            'images.*.alt' => ['nullable', 'string', 'max:255'],
            'images.*.position' => ['nullable', 'integer', 'min:0'],
            'images.*.is_primary' => ['nullable', 'boolean'],
            'attributes' => ['nullable', 'array'],
            'attributes.*.name' => ['required_with:attributes', 'string', 'max:120'],
            'attributes.*.value' => ['required_with:attributes', 'string', 'max:255'],
            'attributes.*.position' => ['nullable', 'integer', 'min:0'],
        ];
    }
}
