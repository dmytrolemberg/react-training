<?php

declare(strict_types = 1);

namespace App\Client\Requests\Api\V1\Profile;

use Illuminate\Foundation\Http\FormRequest;

class StoreAddressRequest extends FormRequest
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
            'label' => ['required', 'string', 'max:80'],
            'first_name' => ['required', 'string', 'max:120'],
            'last_name' => ['required', 'string', 'max:120'],
            'phone' => ['nullable', 'string', 'max:40'],
            'country' => ['nullable', 'string', 'max:120'],
            'city' => ['required', 'string', 'max:120'],
            'postal_code' => ['required', 'string', 'max:32'],
            'address_line' => ['required', 'string', 'max:255'],
            'is_default' => ['sometimes', 'boolean'],
        ];
    }
}
