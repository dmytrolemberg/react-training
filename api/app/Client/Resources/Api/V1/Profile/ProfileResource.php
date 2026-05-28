<?php

declare(strict_types = 1);

namespace App\Client\Resources\Api\V1\Profile;

use App\Models\User\User;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class ProfileResource extends JsonResource
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
            'stats' => [
                'orders_count' => $user->orders_count ?? null,
                'reviews_count' => $user->reviews_count ?? null,
                'cart_items_count' => $user->cart_items_count ?? null,
                'wishlist_count' => $user->wishlist_items_count ?? null,
            ],
            'payment_methods' => $user->relationLoaded('paymentMethods') ? PaymentMethodResource::collection($user->paymentMethods) : [],
        ];
    }
}
