<?php

declare(strict_types = 1);

namespace App\Client\Requests\Api\V1\Catalog;

use Illuminate\Validation\Rule;
use Illuminate\Foundation\Http\FormRequest;

class ProductIndexRequest extends FormRequest
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
            'search' => ['nullable', 'string', 'max:100'],
            'categories' => ['nullable', 'array'],
            'categories.*' => ['string', Rule::exists('categories', 'slug')],
            'brands' => ['nullable', 'array'],
            'brands.*' => ['string', Rule::exists('brands', 'slug')],
            'min_rating' => ['nullable', 'numeric', 'min:0', 'max:5'],
            'in_stock' => ['nullable', 'boolean'],
            'sort' => ['nullable', Rule::in(['featured', 'price_asc', 'price_desc', 'rating_desc', 'priceAsc', 'priceDesc', 'ratingDesc'])],
            'per_page' => ['nullable', 'integer', 'min:1', 'max:50'],
        ];
    }

    public function perPage(): int
    {
        return (int) $this->integer('per_page', 12);
    }

    public function sort(): string
    {
        $sort = $this->input('sort', 'featured');
        $sort = is_string($sort) ? $sort : 'featured';

        return match ($sort) {
            'priceAsc' => 'price_asc',
            'priceDesc' => 'price_desc',
            'ratingDesc' => 'rating_desc',
            default => $sort,
        };
    }
}
