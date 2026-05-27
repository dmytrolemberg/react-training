<?php

declare(strict_types = 1);

namespace App\Admin\Resources\Api\V1\Auth;

use App\Models\User\User;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class UserResource extends JsonResource
{
    /**
     * @return array{id: mixed, name: string, email: string, role: string}
     */
    #[\Override]
    public function toArray(Request $request): array
    {
        /** @var User $user */
        $user = $this->resource;

        return [
            'id' => $user->getKey(),
            'name' => $user->name,
            'email' => $user->email,
            'role' => $user->role,
        ];
    }
}
