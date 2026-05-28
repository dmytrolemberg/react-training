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
        $cart->loadMissing(['items.product.images', 'items.product.attributes']);

        $subtotal = 0;
        $itemCount = 0;
        foreach ($cart->items as $item) {
            $subtotal += $item->lineTotalCents();
            $itemCount += $item->quantity;
        }

        $tax = (int) round($subtotal * 0.08);
        $configuredCurrency = config('app.currency', 'EUR');
        $currency = is_string($configuredCurrency) ? $configuredCurrency : 'EUR';

        return [
            'id' => $cart->id,
            'currency' => $currency,
            'item_count' => $itemCount,
            'items' => CartItemResource::collection($cart->items),
            'summary' => [
                'subtotal' => $this->money($subtotal),
                'tax' => $this->money($tax),
                'delivery' => $this->money(0),
                'total' => $this->money($subtotal + $tax),
            ],
        ];
    }
}
