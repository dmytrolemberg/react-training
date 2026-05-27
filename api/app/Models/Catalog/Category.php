<?php

declare(strict_types = 1);

namespace App\Models\Catalog;

use Illuminate\Database\Eloquent\Model;
use Database\Factories\Catalog\CategoryFactory;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Factories\HasFactory;

/**
 * @property int $id
 * @property string $slug
 * @property string $name
 * @property string|null $description
 * @property int $position
 * @property bool $is_active
 */
#[Fillable(['slug', 'name', 'description', 'position', 'is_active'])]
class Category extends Model
{
    /** @use HasFactory<CategoryFactory> */
    use HasFactory;

    protected static function newFactory(): CategoryFactory
    {
        return CategoryFactory::new();
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
            'position' => 'int',
            'is_active' => 'bool',
        ];
    }
}
