<?php

declare(strict_types = 1);

namespace App\Client\Requests\Api\V1\Profile;

use Illuminate\Foundation\Http\FormRequest;

class UpdateAddressRequest extends FormRequest
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
            'label' => ['sometimes', 'string', 'max:80'],
            'first_name' => ['sometimes', 'string', 'max:120'],
            'last_name' => ['sometimes', 'string', 'max:120'],
            'phone' => ['nullable', 'string', 'max:40'],
            'country' => ['sometimes', 'string', 'max:120'],
            'city' => ['sometimes', 'string', 'max:120'],
            'postal_code' => ['sometimes', 'string', 'max:32'],
            'address_line' => ['sometimes', 'string', 'max:255'],
            'is_default' => ['sometimes', 'boolean'],
        ];
    }
}
