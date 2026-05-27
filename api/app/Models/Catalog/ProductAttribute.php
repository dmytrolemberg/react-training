<?php

declare(strict_types = 1);

namespace App\Models\Catalog;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Database\Factories\Catalog\ProductAttributeFactory;

/**
 * @property int $id
 * @property int $product_id
 * @property string $name
 * @property string $value
 * @property int $position
 */
#[Fillable(['product_id', 'name', 'value', 'position'])]
class ProductAttribute extends Model
{
    /** @use HasFactory<ProductAttributeFactory> */
    use HasFactory;

    protected static function newFactory(): ProductAttributeFactory
    {
        return ProductAttributeFactory::new();
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
        ];
    }
}
