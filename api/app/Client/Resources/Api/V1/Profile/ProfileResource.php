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
            'name' => $user->name,
            'email' => $user->email,
            'role' => $user->role,
            'stats' => [
                'orders_count' => $user->orders_count ?? null,
                'reviews_count' => $user->reviews_count ?? null,
                'cart_items_count' => $user->cart_items_count ?? null,
                'wishlist_count' => $user->wishlist_items_count ?? null,
            ],
            'addresses' => $user->relationLoaded('addresses') ? AddressResource::collection($user->addresses) : [],
            'payment_methods' => $user->relationLoaded('paymentMethods') ? PaymentMethodResource::collection($user->paymentMethods) : [],
        ];
    }
}
