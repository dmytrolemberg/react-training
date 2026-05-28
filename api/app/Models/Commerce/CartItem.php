<?php

declare(strict_types = 1);

namespace App\Models\Commerce;

use App\Models\Catalog\Product;
use Illuminate\Database\Eloquent\Model;
use Database\Factories\Commerce\CartItemFactory;
use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Factories\HasFactory;

/**
 * @property int $id
 * @property int $cart_id
 * @property int $product_id
 * @property int $quantity
 * @property int $unit_price_cents
 * @property Cart $cart
 * @property Product $product
 */
#[Fillable(['cart_id', 'product_id', 'quantity', 'unit_price_cents'])]
class CartItem extends Model
{
    /** @use HasFactory<CartItemFactory> */
    use HasFactory;

    protected static function newFactory(): CartItemFactory
    {
        return CartItemFactory::new();
    }

    /**
     * @return BelongsTo<Cart, $this>
     */
    public function cart(): BelongsTo
    {
        return $this->belongsTo(Cart::class);
    }

    /**
     * @return BelongsTo<Product, $this>
     */
    public function product(): BelongsTo
    {
        return $this->belongsTo(Product::class);
    }

    public function lineTotalCents(): int
    {
        return $this->unit_price_cents * $this->quantity;
    }

    /**
     * @return array<string, string>
     */
    #[\Override]
    protected function casts(): array
    {
        return [
            'quantity' => 'int',
            'unit_price_cents' => 'int',
        ];
    }
}
