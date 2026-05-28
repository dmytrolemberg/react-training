<?php

declare(strict_types = 1);

namespace App\Admin\Resources\Api\V1\Customer;

use App\Models\User\User;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class CustomerResource extends JsonResource
{
    /**
     * @return array<string, mixed>
     */
    #[\Override]
    public function toArray(Request $request): array
    {
        /** @var User $user */
        $user = $this->resource;

        return [
            'id' => $user->id,
            'first_name' => $user->first_name,
            'last_name' => $user->last_name,
            'full_name' => $user->full_name,
            'email' => $user->email,
            'avatar_path' => $user->avatar_path,
            'phone' => $user->phone,
            'country' => $user->country,
            'city' => $user->city,
            'postal_code' => $user->postal_code,
            'address_line' => $user->address_line,
            'role' => $user->role,
            'orders_count' => $this->when(isset($user->orders_count), (int) $user->orders_count),
            'reviews_count' => $this->when(isset($user->reviews_count), (int) $user->reviews_count),
            'created_at' => $user->created_at?->toISOString(),
            'updated_at' => $user->updated_at?->toISOString(),
        ];
    }
}
