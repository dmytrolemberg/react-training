<?php

declare(strict_types = 1);

namespace App\Models\Account;

use App\Models\User\User;
use Illuminate\Database\Eloquent\Model;
use Database\Factories\Account\AddressFactory;
use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Factories\HasFactory;

/**
 * @property int $id
 * @property int $user_id
 * @property string $label
 * @property string $first_name
 * @property string $last_name
 * @property string|null $phone
 * @property string $country
 * @property string $city
 * @property string $postal_code
 * @property string $address_line
 * @property bool $is_default
 * @property User $user
 */
#[Fillable([
    'user_id',
    'label',
    'first_name',
    'last_name',
    'phone',
    'country',
    'city',
    'postal_code',
    'address_line',
    'is_default',
])]
class Address extends Model
{
    /** @use HasFactory<AddressFactory> */
    use HasFactory;

    protected static function newFactory(): AddressFactory
    {
        return AddressFactory::new();
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
            'is_default' => 'bool',
        ];
    }
}
