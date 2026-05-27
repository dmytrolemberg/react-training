<?php

declare(strict_types = 1);

namespace App\Client\Requests\Api\V1\Profile;

use Illuminate\Foundation\Http\FormRequest;

class StorePaymentMethodRequest extends FormRequest
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
            'mock_token' => ['required', 'string', 'max:50'],
            'is_default' => ['sometimes', 'boolean'],
            'card_number' => ['prohibited'],
            'card_cvc' => ['prohibited'],
            'card_expiry' => ['prohibited'],
        ];
    }

    /**
     * @return array{mock_token: string, is_default?: bool}
     */
    public function payload(): array
    {
        $validated = $this->validated();
        $payload = [
            'mock_token' => is_string($validated['mock_token'] ?? null) ? $validated['mock_token'] : '',
        ];

        if (array_key_exists('is_default', $validated)) {
            $payload['is_default'] = (bool) $validated['is_default'];
        }

        return $payload;
    }
}
