<?php

declare(strict_types = 1);

namespace App\Client\UseCases\Profile;

use App\Models\User\User;
use App\Models\Account\Address;

class StoreAddressUseCase
{
    /**
     * @param array<string, mixed> $data
     */
    public function execute(User $user, array $data): Address
    {
        $isDefault = (bool) ($data['is_default'] ?? false);
        if ($isDefault) {
            Address::query()->where('user_id', $user->id)->update(['is_default' => false]);
        }

        $address = Address::query()->create($data + [
            'user_id' => $user->id,
            'country' => $data['country'] ?? 'Ukraine',
            'is_default' => $isDefault,
        ]);

        return $address->refresh();
    }
}
