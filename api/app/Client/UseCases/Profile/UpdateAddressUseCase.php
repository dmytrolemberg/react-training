<?php

declare(strict_types = 1);

namespace App\Client\UseCases\Profile;

use App\Models\User\User;
use App\Models\Account\Address;

class UpdateAddressUseCase
{
    /**
     * @param array<string, mixed> $data
     */
    public function execute(User $user, Address $address, array $data): Address
    {
        abort_unless($address->user_id === $user->id, 404);

        if (($data['is_default'] ?? false) === true) {
            Address::query()
                ->where('user_id', $user->id)
                ->whereKeyNot($address->id)
                ->update(['is_default' => false]);
        }

        $address->forceFill($data)->save();

        return $address->refresh();
    }
}
