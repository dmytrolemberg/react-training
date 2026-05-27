<?php

declare(strict_types = 1);

namespace App\Client\Requests\Api\V1\Review;

use Illuminate\Validation\Rule;
use Illuminate\Foundation\Http\FormRequest;

class ReviewIndexRequest extends FormRequest
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
            'rating' => ['nullable', 'integer', 'min:1', 'max:5'],
            'product_id' => ['nullable', 'integer', Rule::exists('products', 'id')],
            'per_page' => ['nullable', 'integer', 'min:1', 'max:50'],
        ];
    }

    public function perPage(): int
    {
        return (int) $this->integer('per_page', 12);
    }
}
