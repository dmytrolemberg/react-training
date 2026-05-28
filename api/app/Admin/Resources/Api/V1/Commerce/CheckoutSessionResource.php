<?php

declare(strict_types = 1);

namespace App\Admin\Resources\Api\V1\Commerce;

use Illuminate\Http\Request;
use App\Models\Commerce\CheckoutSession;
use Illuminate\Http\Resources\Json\JsonResource;
use App\Admin\Resources\Api\V1\Concerns\FormatsMoney;
use App\Admin\Resources\Api\V1\Customer\CustomerResource;

class CheckoutSessionResource extends JsonResource
{
    use FormatsMoney;

    /**
     * @return array<string, mixed>
     */
    #[\Override]
    public function toArray(Request $request): array
    {
        /** @var CheckoutSession $session */
        $session = $this->resource;

        return [
            'id' => $session->id,
            'user_id' => $session->user_id,
            'cart_id' => $session->cart_id,
            'order_id' => $session->order_id,
            'status' => $session->status->value,
            'failure_stage' => $session->failure_stage,
            'failure_reason' => $session->failure_reason,
            'contact_email' => $session->contact_email,
            'delivery_method' => $session->delivery_method,
            'payment_method_type' => $session->payment_method_type,
            'item_count' => $session->item_count,
            'summary' => [
                'subtotal' => $this->money($session->subtotal_cents),
                'delivery' => $this->money($session->delivery_cents),
                'tax' => $this->money($session->tax_cents),
                'total' => $this->money($session->total_cents),
            ],
            'payload' => $session->payload,
            'user' => $session->relationLoaded('user') && $session->user !== null ? new CustomerResource($session->user) : null,
            'order' => $session->relationLoaded('order') && $session->order !== null ? new OrderResource($session->order) : null,
            'completed_at' => $session->completed_at?->toISOString(),
            'failed_at' => $session->failed_at?->toISOString(),
            'created_at' => $session->created_at?->toISOString(),
            'updated_at' => $session->updated_at?->toISOString(),
        ];
    }
}
