<?php

declare(strict_types = 1);

namespace App\Client\Resources\Api\V1\Order;

use Illuminate\Http\Request;
use App\Models\Commerce\OrderStatusEvent;
use Illuminate\Http\Resources\Json\JsonResource;

class OrderStatusEventResource extends JsonResource
{
    /**
     * @return array<string, mixed>
     */
    #[\Override]
    public function toArray(Request $request): array
    {
        /** @var OrderStatusEvent $event */
        $event = $this->resource;

        return [
            'id' => $event->id,
            'status' => $event->status->value,
            'label' => $event->label,
            'description' => $event->description,
            'occurred_at' => $event->occurred_at?->toISOString(),
            'position' => $event->position,
        ];
    }
}
