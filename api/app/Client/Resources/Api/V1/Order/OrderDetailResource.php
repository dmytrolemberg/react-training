<?php

declare(strict_types = 1);

namespace App\Client\Resources\Api\V1\Order;

use Illuminate\Http\Request;
use App\Models\Commerce\Order;
use App\Client\Resources\Api\V1\Concerns\FormatsMoney;

class OrderDetailResource extends OrderSummaryResource
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
        $data = parent::toArray($request);

        $data['contact'] = [
            'email' => $order->contact_email,
            'phone' => $order->contact_phone,
        ];
        $data['shipping_address'] = [
            'first_name' => $order->shipping_first_name,
            'last_name' => $order->shipping_last_name,
            'country' => $order->shipping_country,
            'city' => $order->shipping_city,
            'postal_code' => $order->shipping_postal_code,
            'address_line' => $order->shipping_address_line,
        ];
        $data['delivery_method'] = [
            'value' => $order->delivery_method->value,
            'label' => $order->delivery_method->label(),
        ];
        $data['payment'] = [
            'method_type' => $order->payment_method_type,
            'method_label' => $order->payment_method_label,
            'transaction_id' => $order->transaction_id,
        ];
        $data['summary'] = [
            'subtotal' => $this->money($order->subtotal_cents, $order->currency),
            'delivery' => $this->money($order->delivery_cents, $order->currency),
            'tax' => $this->money($order->tax_cents, $order->currency),
            'total' => $this->money($order->total_cents, $order->currency),
        ];
        $data['items'] = $order->relationLoaded('items') ? OrderItemResource::collection($order->items) : [];
        $data['timeline'] = $order->relationLoaded('statusEvents') ? OrderStatusEventResource::collection($order->statusEvents) : [];

        return $data;
    }
}
