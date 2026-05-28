<?php

declare(strict_types = 1);

namespace App\Client\Resources\Api\V1\Auth;

use App\Models\User\User;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class UserResource extends JsonResource
{
    /**
     * @return array{id: mixed, first_name: string, last_name: string, full_name: string, email: string, avatar_path: string|null, role: string}
     */
    #[\Override]
    public function toArray(Request $request): array
    {
        /** @var User $user */
        $user = $this->resource;

        return [
            'id' => $user->getKey(),
            'first_name' => $user->first_name,
            'last_name' => $user->last_name,
            'full_name' => $user->full_name,
            'email' => $user->email,
            'avatar_path' => $user->avatar_path,
            'role' => $user->role,
        ];
    }
}
