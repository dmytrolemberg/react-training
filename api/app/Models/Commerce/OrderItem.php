<?php

declare(strict_types = 1);

namespace App\Models\Commerce;

use App\Models\Catalog\Product;
use Illuminate\Database\Eloquent\Model;
use Database\Factories\Commerce\OrderItemFactory;
use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Factories\HasFactory;

/**
 * @property int $id
 * @property int $order_id
 * @property int|null $product_id
 * @property string $product_slug
 * @property string $sku
 * @property string $name
 * @property string $brand_name
 * @property string $category_name
 * @property int $unit_price_cents
 * @property int $quantity
 * @property int $total_cents
 * @property array<int, array{name: string, value: string}> $attributes
 * @property Order $order
 * @property Product|null $product
 */
#[Fillable([
    'order_id',
    'product_id',
    'product_slug',
    'sku',
    'name',
    'brand_name',
    'category_name',
    'unit_price_cents',
    'quantity',
    'total_cents',
    'attributes',
])]
class OrderItem extends Model
{
    /** @use HasFactory<OrderItemFactory> */
    use HasFactory;

    protected static function newFactory(): OrderItemFactory
    {
        return OrderItemFactory::new();
    }

    /**
     * @return BelongsTo<Order, $this>
     */
    public function order(): BelongsTo
    {
        return $this->belongsTo(Order::class);
    }

    /**
     * @return BelongsTo<Product, $this>
     */
    public function product(): BelongsTo
    {
        return $this->belongsTo(Product::class);
    }

    /**
     * @return array<string, string>
     */
    #[\Override]
    protected function casts(): array
    {
        return [
            'unit_price_cents' => 'int',
            'quantity' => 'int',
            'total_cents' => 'int',
            'attributes' => 'array',
        ];
    }
}
