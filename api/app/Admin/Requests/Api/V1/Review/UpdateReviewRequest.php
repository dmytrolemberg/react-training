<?php

declare(strict_types = 1);

namespace App\Admin\Requests\Api\V1\Review;

use Illuminate\Validation\Rule;
use App\Models\Catalog\ReviewStatus;
use Illuminate\Foundation\Http\FormRequest;

class UpdateReviewRequest extends FormRequest
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
            'product_id' => ['sometimes', 'integer', Rule::exists('products', 'id')],
            'user_id' => ['nullable', 'integer', Rule::exists('users', 'id')],
            'order_item_id' => ['nullable', 'integer', Rule::exists('order_items', 'id')],
            'rating' => ['sometimes', 'integer', 'min:1', 'max:5'],
            'body' => ['sometimes', 'string', 'min:5', 'max:5000'],
            'author_name' => ['nullable', 'string', 'max:120'],
            'status' => ['sometimes', Rule::enum(ReviewStatus::class)],
            'is_verified_purchase' => ['sometimes', 'boolean'],
        ];
    }
}
