<?php

declare(strict_types = 1);

namespace App\Models\Catalog;

use Illuminate\Database\Eloquent\Model;
use Database\Factories\Catalog\BrandFactory;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Factories\HasFactory;

/**
 * @property int $id
 * @property string $slug
 * @property string $name
 * @property string|null $description
 * @property string|null $logo_initial
 * @property bool $is_active
 */
#[Fillable(['slug', 'name', 'description', 'logo_initial', 'is_active'])]
class Brand extends Model
{
    /** @use HasFactory<BrandFactory> */
    use HasFactory;

    protected static function newFactory(): BrandFactory
    {
        return BrandFactory::new();
    }

    /**
     * @return HasMany<Product, $this>
     */
    public function products(): HasMany
    {
        return $this->hasMany(Product::class);
    }

    /**
     * @return array<string, string>
     */
    #[\Override]
    protected function casts(): array
    {
        return [
            'is_active' => 'bool',
        ];
    }
}
