<?php

declare(strict_types = 1);

namespace App\Client\Requests\Api\V1\Profile;

use Illuminate\Validation\Rule;
use Illuminate\Foundation\Http\FormRequest;

class StoreWishlistItemRequest extends FormRequest
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
        ];
    }

    public function productId(): int
    {
        return (int) $this->integer('product_id');
    }
}
