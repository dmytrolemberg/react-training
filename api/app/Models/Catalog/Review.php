<?php

declare(strict_types = 1);

namespace App\Models\Catalog;

use App\Models\User\User;
use App\Models\Commerce\OrderItem;
use Illuminate\Database\Eloquent\Model;
use Database\Factories\Catalog\ReviewFactory;
use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Factories\HasFactory;

/**
 * @property int $id
 * @property int $product_id
 * @property int|null $user_id
 * @property int|null $order_item_id
 * @property int $rating
 * @property string $body
 * @property string|null $author_name
 * @property ReviewStatus $status
 * @property bool $is_verified_purchase
 * @property Product $product
 * @property User|null $user
 */
#[Fillable([
    'product_id',
    'user_id',
    'order_item_id',
    'rating',
    'body',
    'author_name',
    'status',
    'is_verified_purchase',
])]
class Review extends Model
{
    /** @use HasFactory<ReviewFactory> */
    use HasFactory;

    protected static function newFactory(): ReviewFactory
    {
        return ReviewFactory::new();
    }

    /**
     * @return BelongsTo<Product, $this>
     */
    public function product(): BelongsTo
    {
        return $this->belongsTo(Product::class);
    }

    /**
     * @return BelongsTo<User, $this>
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    /**
     * @return BelongsTo<OrderItem, $this>
     */
    public function orderItem(): BelongsTo
    {
        return $this->belongsTo(OrderItem::class);
    }

    /**
     * @return array<string, string>
     */
    #[\Override]
    protected function casts(): array
    {
        return [
            'rating' => 'int',
            'status' => ReviewStatus::class,
            'is_verified_purchase' => 'bool',
        ];
    }
}
