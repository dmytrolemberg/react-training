<?php

declare(strict_types = 1);

namespace App\Client\Resources\Api\V1\Cart;

use Illuminate\Http\Request;
use App\Models\Commerce\Cart;
use App\Client\Services\Cart\CartPricingService;
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

        $summary = resolve(CartPricingService::class)->summarize($cart);

        return [
            'id' => $cart->id,
            'currency' => resolve(\App\Admin\Services\ShopSettingsService::class)->currency(),
            'item_count' => $summary['item_count'],
            'items' => CartItemResource::collection($cart->items),
            'summary' => [
                'subtotal' => $this->money($summary['subtotal_cents']),
                'tax' => $this->money($summary['tax_cents']),
                'delivery' => $this->money($summary['delivery_cents']),
                'total' => $this->money($summary['total_cents']),
            ],
        ];
    }
}
