<?php

declare(strict_types = 1);

namespace App\Client\UseCases\Profile;

use App\Models\User\User;

class UpdateProfileUseCase
{
    /**
     * @param array{name: string, email: string} $data
     */
    public function execute(User $user, array $data): User
    {
        $user = User::query()->findOrFail($user->id);
        $user->forceFill($data)->save();

        return $user->refresh();
    }
}
