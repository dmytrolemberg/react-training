<?php

declare(strict_types = 1);

namespace App\Models\Account;

use App\Models\User\User;
use Illuminate\Database\Eloquent\Model;
use Database\Factories\Account\PaymentMethodFactory;
use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Factories\HasFactory;

/**
 * @property int $id
 * @property int $user_id
 * @property string $type
 * @property string $label
 * @property string|null $brand
 * @property string|null $last_four
 * @property int|null $expires_month
 * @property int|null $expires_year
 * @property string|null $mock_token
 * @property bool $is_default
 * @property User $user
 */
#[Fillable([
    'user_id',
    'type',
    'label',
    'brand',
    'last_four',
    'expires_month',
    'expires_year',
    'mock_token',
    'is_default',
])]
class PaymentMethod extends Model
{
    /** @use HasFactory<PaymentMethodFactory> */
    use HasFactory;

    protected static function newFactory(): PaymentMethodFactory
    {
        return PaymentMethodFactory::new();
    }

    /**
     * @return BelongsTo<User, $this>
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    /**
     * @return array<string, string>
     */
    #[\Override]
    protected function casts(): array
    {
        return [
            'expires_month' => 'int',
            'expires_year' => 'int',
            'is_default' => 'bool',
        ];
    }
}
