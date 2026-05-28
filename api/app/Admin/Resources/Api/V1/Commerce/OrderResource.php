<?php

declare(strict_types = 1);

namespace App\Admin\Resources\Api\V1\Commerce;

use Illuminate\Http\Request;
use App\Models\Commerce\Order;
use Illuminate\Http\Resources\Json\JsonResource;
use App\Admin\Resources\Api\V1\Concerns\FormatsMoney;
use App\Admin\Resources\Api\V1\Customer\CustomerResource;

class OrderResource extends JsonResource
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
            'user_id' => $order->user_id,
            'number' => $order->number,
            'status' => $order->status->value,
            'payment_status' => $order->payment_status->value,
            'contact' => [
                'email' => $order->contact_email,
                'phone' => $order->contact_phone,
            ],
            'shipping_address' => [
                'first_name' => $order->shipping_first_name,
                'last_name' => $order->shipping_last_name,
                'country' => $order->shipping_country,
                'city' => $order->shipping_city,
                'postal_code' => $order->shipping_postal_code,
                'address_line' => $order->shipping_address_line,
            ],
            'delivery_method' => [
                'value' => $order->delivery_method->value,
                'label' => $order->delivery_method->label(),
            ],
            'payment' => [
                'method_type' => $order->payment_method_type,
                'method_label' => $order->payment_method_label,
                'transaction_id' => $order->transaction_id,
            ],
            'summary' => [
                'subtotal' => $this->money($order->subtotal_cents),
                'delivery' => $this->money($order->delivery_cents),
                'tax' => $this->money($order->tax_cents),
                'total' => $this->money($order->total_cents),
            ],
            'user' => $order->relationLoaded('user') ? new CustomerResource($order->user) : null,
            'items' => $order->relationLoaded('items') ? $order->items->map(fn($item): array => [
                'id' => $item->id,
                'product_id' => $item->product_id,
                'product_slug' => $item->product_slug,
                'sku' => $item->sku,
                'name' => $item->name,
                'brand_name' => $item->brand_name,
                'category_name' => $item->category_name,
                'unit_price' => $this->money($item->unit_price_cents),
                'quantity' => $item->quantity,
                'total' => $this->money($item->total_cents),
                'attributes' => $item->attributes,
            ])->values()->all() : [],
            'timeline' => $order->relationLoaded('statusEvents') ? $order->statusEvents->map(fn($event): array => [
                'id' => $event->id,
                'status' => $event->status->value,
                'label' => $event->label,
                'description' => $event->description,
                'occurred_at' => $event->occurred_at?->toISOString(),
                'position' => $event->position,
            ])->values()->all() : [],
            'placed_at' => $order->placed_at?->toISOString(),
            'created_at' => $order->created_at?->toISOString(),
            'updated_at' => $order->updated_at?->toISOString(),
        ];
    }
}
