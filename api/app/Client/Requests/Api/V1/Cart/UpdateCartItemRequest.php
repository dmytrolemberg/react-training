<?php

declare(strict_types = 1);

namespace App\Client\Requests\Api\V1\Cart;

use Illuminate\Foundation\Http\FormRequest;

class UpdateCartItemRequest extends FormRequest
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
            'quantity' => ['required', 'integer', 'min:1', 'max:50'],
        ];
    }

    public function quantity(): int
    {
        return (int) $this->integer('quantity');
    }
}
