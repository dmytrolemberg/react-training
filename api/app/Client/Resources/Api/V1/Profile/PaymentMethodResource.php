<?php

declare(strict_types = 1);

namespace App\Client\Resources\Api\V1\Profile;

use Illuminate\Http\Request;
use App\Models\Account\PaymentMethod;
use Illuminate\Http\Resources\Json\JsonResource;

class PaymentMethodResource extends JsonResource
{
    /**
     * @return array<string, mixed>
     */
    #[\Override]
    public function toArray(Request $request): array
    {
        /** @var PaymentMethod $paymentMethod */
        $paymentMethod = $this->resource;

        return [
            'id' => $paymentMethod->id,
            'type' => $paymentMethod->type,
            'label' => $paymentMethod->label,
            'brand' => $paymentMethod->brand,
            'last_four' => $paymentMethod->last_four,
            'expires_month' => $paymentMethod->expires_month,
            'expires_year' => $paymentMethod->expires_year,
            'is_default' => $paymentMethod->is_default,
        ];
    }
}
