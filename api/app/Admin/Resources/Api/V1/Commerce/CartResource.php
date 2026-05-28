<?php

declare(strict_types = 1);

namespace App\Admin\Resources\Api\V1\Commerce;

use Illuminate\Http\Request;
use App\Models\Commerce\Cart;
use App\Client\Services\Cart\CartPricingService;
use Illuminate\Http\Resources\Json\JsonResource;
use App\Admin\Resources\Api\V1\Concerns\FormatsMoney;
use App\Admin\Resources\Api\V1\Customer\CustomerResource;

class CartResource extends JsonResource
{
    use FormatsMoney;

    /**
     * @return array<string, mixed>
     */
    #[\Override]
    public function toArray(Request $request): array
    {
        /** @var Cart $cart */
        $cart = $this->resource;
        $cart->loadMissing('items.product');

        $summary = resolve(CartPricingService::class)->summarize($cart);

        return [
            'id' => $cart->id,
            'user_id' => $cart->user_id,
            'user' => $cart->relationLoaded('user') ? new CustomerResource($cart->user) : null,
            'item_count' => $summary['item_count'],
            'summary' => [
                'subtotal' => $this->money($summary['subtotal_cents']),
                'delivery' => $this->money($summary['delivery_cents']),
                'tax' => $this->money($summary['tax_cents']),
                'total' => $this->money($summary['total_cents']),
            ],
            'items' => $cart->items->map(fn($item): array => [
                'id' => $item->id,
                'product_id' => $item->product_id,
                'product_name' => $item->product->name,
                'sku' => $item->product->sku,
                'quantity' => $item->quantity,
                'unit_price' => $this->money($item->unit_price_cents),
                'total' => $this->money($item->lineTotalCents()),
            ])->values()->all(),
            'created_at' => $cart->created_at?->toISOString(),
            'updated_at' => $cart->updated_at?->toISOString(),
        ];
    }
}
