<?php

declare(strict_types = 1);

namespace App\Client\Resources\Api\V1\Profile;

use Illuminate\Http\Request;
use App\Models\Account\Address;
use Illuminate\Http\Resources\Json\JsonResource;

class AddressResource extends JsonResource
{
    /**
     * @return array<string, mixed>
     */
    #[\Override]
    public function toArray(Request $request): array
    {
        /** @var Address $address */
        $address = $this->resource;

        return [
            'id' => $address->id,
            'label' => $address->label,
            'first_name' => $address->first_name,
            'last_name' => $address->last_name,
            'phone' => $address->phone,
            'country' => $address->country,
            'city' => $address->city,
            'postal_code' => $address->postal_code,
            'address_line' => $address->address_line,
            'is_default' => $address->is_default,
        ];
    }
}
