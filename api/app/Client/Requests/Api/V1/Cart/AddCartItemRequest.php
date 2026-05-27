<?php

declare(strict_types = 1);

namespace App\Client\Requests\Api\V1\Cart;

use Illuminate\Validation\Rule;
use Illuminate\Foundation\Http\FormRequest;

class AddCartItemRequest extends FormRequest
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
            'quantity' => ['required', 'integer', 'min:1', 'max:50'],
        ];
    }

    public function productId(): int
    {
        return (int) $this->integer('product_id');
    }

    public function quantity(): int
    {
        return (int) $this->integer('quantity');
    }
}
