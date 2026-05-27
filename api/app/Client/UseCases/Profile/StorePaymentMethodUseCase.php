<?php

declare(strict_types = 1);

namespace App\Client\UseCases\Profile;

use App\Models\User\User;
use App\Models\Account\PaymentMethod;
use Illuminate\Validation\ValidationException;

class StorePaymentMethodUseCase
{
    /**
     * @param array{mock_token: string, is_default?: bool} $data
     * @throws ValidationException
     */
    public function execute(User $user, array $data): PaymentMethod
    {
        $existing = PaymentMethod::query()->where('mock_token', $data['mock_token'])->first();
        if ($existing instanceof PaymentMethod) {
            throw ValidationException::withMessages(['mock_token' => ['This mock payment method already exists.']]);
        }

        $details = $this->detailsFromToken($data['mock_token']);
        $isDefault = (bool) ($data['is_default'] ?? false);

        if ($isDefault) {
            PaymentMethod::query()->where('user_id', $user->id)->update(['is_default' => false]);
        }

        return PaymentMethod::query()->create($details + [
            'user_id' => $user->id,
            'mock_token' => $data['mock_token'],
            'is_default' => $isDefault,
        ]);
    }

    /**
     * @return array{type: string, label: string, brand: string, last_four: string, expires_month: int, expires_year: int}
     * @throws ValidationException
     */
    private function detailsFromToken(string $mockToken): array
    {
        if (!preg_match('/^pm_mock_(visa|mastercard|card)_(\d{4})$/', $mockToken, $matches)) {
            throw ValidationException::withMessages(['mock_token' => ['Use a mock token such as pm_mock_visa_4242.']]);
        }

        $brand = match ($matches[1]) {
            'visa' => 'Visa',
            'mastercard' => 'Mastercard',
            default => 'Mock card',
        };

        return [
            'type' => 'mock_card',
            'label' => $brand . ' ending ' . $matches[2],
            'brand' => $brand,
            'last_four' => $matches[2],
            'expires_month' => 12,
            'expires_year' => 2028,
        ];
    }
}
