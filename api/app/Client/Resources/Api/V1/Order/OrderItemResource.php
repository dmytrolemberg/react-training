<?php

declare(strict_types = 1);

namespace App\Client\Resources\Api\V1\Order;

use Illuminate\Http\Request;
use App\Models\Commerce\OrderItem;
use Illuminate\Http\Resources\Json\JsonResource;
use App\Client\Resources\Api\V1\Concerns\FormatsMoney;

class OrderItemResource extends JsonResource
{
    use FormatsMoney;

    /**
     * @return array<string, mixed>
     */
    #[\Override]
    public function toArray(Request $request): array
    {
        /** @var OrderItem $item */
        $item = $this->resource;

        return [
            'id' => $item->id,
            'product_id' => $item->product_id,
            'product_slug' => $item->product_slug,
            'sku' => $item->sku,
            'name' => $item->name,
            'brand_name' => $item->brand_name,
            'category_name' => $item->category_name,
            'quantity' => $item->quantity,
            'unit_price' => $this->money($item->unit_price_cents, 'USD'),
            'line_total' => $this->money($item->total_cents, 'USD'),
            'attributes' => $item->attributes,
        ];
    }
}
