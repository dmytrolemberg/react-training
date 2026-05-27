<?php

declare(strict_types = 1);

namespace App\Client\Resources\Api\V1\Order;

use Illuminate\Http\Request;
use App\Models\Commerce\Order;
use Illuminate\Http\Resources\Json\JsonResource;
use App\Client\Resources\Api\V1\Concerns\FormatsMoney;

class OrderSummaryResource extends JsonResource
{
    use FormatsMoney;

    /**
     * @return array<string, mixed>
     */
    #[\Override]
    public function toArray(Request $request): array
    {
        /** @var Order $order */
        $order = $this->resource;

        return [
            'id' => $order->id,
            'number' => $order->number,
            'status' => $order->status->value,
            'payment_status' => $order->payment_status->value,
            'placed_at' => $order->placed_at?->toISOString(),
            'items_count' => $order->relationLoaded('items') ? $order->items->sum('quantity') : null,
            'items_preview' => $order->relationLoaded('items') ? $order->items->pluck('name')->values()->all() : [],
            'total' => $this->money($order->total_cents, $order->currency),
        ];
    }
}
