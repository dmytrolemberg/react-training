<?php

declare(strict_types = 1);

namespace App\Client\UseCases\Profile;

use App\Models\User\User;
use App\Models\Account\Address;

class DeleteAddressUseCase
{
    public function execute(User $user, Address $address): void
    {
        abort_unless($address->user_id === $user->id, 404);

        $address->delete();
    }
}
