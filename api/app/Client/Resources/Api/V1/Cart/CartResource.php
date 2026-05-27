<?php

declare(strict_types = 1);

namespace App\Client\Resources\Api\V1\Cart;

use Illuminate\Http\Request;
use App\Models\Commerce\Cart;
use Illuminate\Http\Resources\Json\JsonResource;
use App\Client\Resources\Api\V1\Concerns\FormatsMoney;

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
        $cart->loadMissing(['items.product.brand', 'items.product.category', 'items.product.images', 'items.product.attributes']);

        $subtotal = 0;
        $itemCount = 0;
        foreach ($cart->items as $item) {
            $subtotal += $item->lineTotalCents();
            $itemCount += $item->quantity;
        }

        $tax = (int) round($subtotal * 0.08);

        return [
            'id' => $cart->id,
            'status' => $cart->status->value,
            'currency' => $cart->currency,
            'item_count' => $itemCount,
            'items' => CartItemResource::collection($cart->items),
            'summary' => [
                'subtotal' => $this->money($subtotal, $cart->currency),
                'tax' => $this->money($tax, $cart->currency),
                'delivery' => $this->money(0, $cart->currency),
                'total' => $this->money($subtotal + $tax, $cart->currency),
            ],
        ];
    }
}
