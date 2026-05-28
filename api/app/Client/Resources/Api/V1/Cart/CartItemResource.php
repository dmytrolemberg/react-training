<?php

declare(strict_types = 1);

namespace App\Client\Resources\Api\V1\Cart;

use Illuminate\Http\Request;
use App\Models\Commerce\CartItem;
use Illuminate\Http\Resources\Json\JsonResource;
use App\Client\Resources\Api\V1\Concerns\FormatsMoney;

class CartItemResource extends JsonResource
{
    use FormatsMoney;

    /**
     * @return array<string, mixed>
     */
    #[\Override]
    public function toArray(Request $request): array
    {
        /** @var CartItem $item */
        $item = $this->resource;

        return [
            'id' => $item->id,
            'quantity' => $item->quantity,
            'unit_price' => $this->money($item->unit_price_cents),
            'line_total' => $this->money($item->lineTotalCents()),
            'product' => new CartProductResource($item->product),
        ];
    }
}
