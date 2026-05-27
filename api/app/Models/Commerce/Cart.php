<?php

declare(strict_types = 1);

namespace App\Models\Commerce;

use App\Models\User\User;
use Illuminate\Database\Eloquent\Model;
use Database\Factories\Commerce\CartFactory;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Factories\HasFactory;

/**
 * @property int $id
 * @property int $user_id
 * @property CartStatus $status
 * @property string $currency
 * @property User $user
 */
#[Fillable(['user_id', 'status', 'currency'])]
class Cart extends Model
{
    /** @use HasFactory<CartFactory> */
    use HasFactory;

    protected static function newFactory(): CartFactory
    {
        return CartFactory::new();
    }

    /**
     * @return BelongsTo<User, $this>
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    /**
     * @return HasMany<CartItem, $this>
     */
    public function items(): HasMany
    {
        return $this->hasMany(CartItem::class);
    }

    /**
     * @return array<string, string>
     */
    #[\Override]
    protected function casts(): array
    {
        return [
            'status' => CartStatus::class,
        ];
    }
}
