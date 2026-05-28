<?php

declare(strict_types = 1);

namespace App\Admin\Requests\Api\V1\Review;

use Illuminate\Validation\Rule;
use App\Models\Catalog\ReviewStatus;
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
            'search' => ['nullable', 'string', 'max:100'],
            'status' => ['nullable', Rule::enum(ReviewStatus::class)],
            'product_id' => ['nullable', 'integer', Rule::exists('products', 'id')],
            'rating' => ['nullable', 'integer', 'min:1', 'max:5'],
            'per_page' => ['nullable', 'integer', 'min:1', 'max:100'],
        ];
    }

    public function perPage(): int
    {
        return (int) $this->integer('per_page', 15);
    }
}
