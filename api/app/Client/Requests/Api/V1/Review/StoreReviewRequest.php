<?php

declare(strict_types = 1);

namespace App\Client\Requests\Api\V1\Review;

use Illuminate\Validation\Rule;
use Illuminate\Foundation\Http\FormRequest;

class StoreReviewRequest extends FormRequest
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
            'product_id' => ['required', 'integer', Rule::exists('products', 'id')],
            'rating' => ['required', 'integer', 'min:1', 'max:5'],
            'body' => ['required', 'string', 'min:5', 'max:2000'],
            'order_item_id' => ['nullable', 'integer', Rule::exists('order_items', 'id')],
        ];
    }

    public function productId(): int
    {
        return (int) $this->integer('product_id');
    }

    /**
     * @return array{product_id: int, rating: int, body: string, order_item_id?: int|null}
     */
    public function payload(): array
    {
        $validated = $this->validated();
        $productId = $validated['product_id'] ?? 0;
        $rating = $validated['rating'] ?? 0;
        $payload = [
            'product_id' => is_numeric($productId) ? (int) $productId : 0,
            'rating' => is_numeric($rating) ? (int) $rating : 0,
            'body' => is_string($validated['body'] ?? null) ? $validated['body'] : '',
        ];

        if (array_key_exists('order_item_id', $validated)) {
            $orderItemId = $validated['order_item_id'];
            $payload['order_item_id'] = $validated['order_item_id'] === null
                ? null
                : (is_numeric($orderItemId) ? (int) $orderItemId : null);
        }

        return $payload;
    }
}
