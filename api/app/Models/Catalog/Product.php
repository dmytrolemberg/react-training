<?php

declare(strict_types = 1);

namespace App\Models\Catalog;

use Illuminate\Database\Eloquent\Model;
use Database\Factories\Catalog\ProductFactory;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Factories\HasFactory;

/**
 * @property int $id
 * @property int $brand_id
 * @property int $category_id
 * @property string $slug
 * @property string $sku
 * @property string $name
 * @property string $short_description
 * @property string $description_html
 * @property int $price_cents
 * @property int $stock_quantity
 * @property bool $is_active
 * @property float $rating_average
 * @property int $reviews_count
 * @property Brand $brand
 * @property Category $category
 */
#[Fillable([
    'brand_id',
    'category_id',
    'slug',
    'sku',
    'name',
    'short_description',
    'description_html',
    'price_cents',
    'stock_quantity',
    'is_active',
    'rating_average',
    'reviews_count',
])]
class Product extends Model
{
    /** @use HasFactory<ProductFactory> */
    use HasFactory;

    protected static function newFactory(): ProductFactory
    {
        return ProductFactory::new();
    }

    /**
     * @return BelongsTo<Brand, $this>
     */
    public function brand(): BelongsTo
    {
        return $this->belongsTo(Brand::class);
    }

    /**
     * @return BelongsTo<Category, $this>
     */
    public function category(): BelongsTo
    {
        return $this->belongsTo(Category::class);
    }

    /**
     * @return HasMany<ProductImage, $this>
     */
    public function images(): HasMany
    {
        return $this->hasMany(ProductImage::class)->orderBy('position');
    }

    /**
     * @return HasMany<ProductAttribute, $this>
     */
    public function attributes(): HasMany
    {
        return $this->hasMany(ProductAttribute::class)->orderBy('position');
    }

    /**
     * @return HasMany<Review, $this>
     */
    public function reviews(): HasMany
    {
        return $this->hasMany(Review::class);
    }

    public function inStock(int $quantity = 1): bool
    {
        return $this->is_active && $this->stock_quantity >= $quantity;
    }

    /**
     * @return array<string, string>
     */
    #[\Override]
    protected function casts(): array
    {
        return [
            'price_cents' => 'int',
            'stock_quantity' => 'int',
            'is_active' => 'bool',
            'rating_average' => 'float',
            'reviews_count' => 'int',
        ];
    }
}
