<?php

declare(strict_types = 1);

namespace App\Models\Account;

use App\Models\User\User;
use App\Models\Catalog\Product;
use Illuminate\Database\Eloquent\Model;
use Database\Factories\Account\WishlistItemFactory;
use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Factories\HasFactory;

/**
 * @property int $id
 * @property int $user_id
 * @property int $product_id
 * @property User $user
 * @property Product $product
 */
#[Fillable(['user_id', 'product_id'])]
class WishlistItem extends Model
{
    /** @use HasFactory<WishlistItemFactory> */
    use HasFactory;

    protected static function newFactory(): WishlistItemFactory
    {
        return WishlistItemFactory::new();
    }

    /**
     * @return BelongsTo<User, $this>
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    /**
     * @return BelongsTo<Product, $this>
     */
    public function product(): BelongsTo
    {
        return $this->belongsTo(Product::class);
    }
}
