<?php

declare(strict_types = 1);

namespace App\Models\Catalog;

use Illuminate\Database\Eloquent\Model;
use Database\Factories\Catalog\ProductImageFactory;
use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Factories\HasFactory;

/**
 * @property int $id
 * @property int $product_id
 * @property string $url
 * @property string|null $alt
 * @property int $position
 * @property bool $is_primary
 */
#[Fillable(['product_id', 'url', 'alt', 'position', 'is_primary'])]
class ProductImage extends Model
{
    /** @use HasFactory<ProductImageFactory> */
    use HasFactory;

    protected static function newFactory(): ProductImageFactory
    {
        return ProductImageFactory::new();
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
            'position' => 'int',
            'is_primary' => 'bool',
        ];
    }
}
