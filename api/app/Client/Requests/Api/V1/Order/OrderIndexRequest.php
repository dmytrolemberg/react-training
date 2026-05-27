<?php

declare(strict_types = 1);

namespace App\Client\Requests\Api\V1\Order;

use Illuminate\Validation\Rule;
use App\Models\Commerce\OrderStatus;
use Illuminate\Foundation\Http\FormRequest;

class OrderIndexRequest extends FormRequest
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
            'status' => ['nullable', Rule::enum(OrderStatus::class)],
            'per_page' => ['nullable', 'integer', 'min:1', 'max:50'],
        ];
    }

    public function perPage(): int
    {
        return (int) $this->integer('per_page', 10);
    }
}
