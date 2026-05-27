<?php

declare(strict_types = 1);

namespace App\Models\Commerce;

use App\Models\User\User;
use Illuminate\Support\Carbon;
use Illuminate\Database\Eloquent\Model;
use Database\Factories\Commerce\OrderFactory;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Factories\HasFactory;

/**
 * @property int $id
 * @property int $user_id
 * @property string $number
 * @property OrderStatus $status
 * @property PaymentStatus $payment_status
 * @property string $currency
 * @property int $subtotal_cents
 * @property int $delivery_cents
 * @property int $tax_cents
 * @property int $total_cents
 * @property string $contact_email
 * @property string|null $contact_phone
 * @property string $shipping_first_name
 * @property string $shipping_last_name
 * @property string $shipping_country
 * @property string $shipping_city
 * @property string $shipping_postal_code
 * @property string $shipping_address_line
 * @property DeliveryMethod $delivery_method
 * @property string $payment_method_type
 * @property string $payment_method_label
 * @property string|null $transaction_id
 * @property Carbon|null $placed_at
 * @property User $user
 */
#[Fillable([
    'user_id',
    'number',
    'status',
    'payment_status',
    'currency',
    'subtotal_cents',
    'delivery_cents',
    'tax_cents',
    'total_cents',
    'contact_email',
    'contact_phone',
    'shipping_first_name',
    'shipping_last_name',
    'shipping_country',
    'shipping_city',
    'shipping_postal_code',
    'shipping_address_line',
    'delivery_method',
    'payment_method_type',
    'payment_method_label',
    'transaction_id',
    'placed_at',
])]
class Order extends Model
{
    /** @use HasFactory<OrderFactory> */
    use HasFactory;

    protected static function newFactory(): OrderFactory
    {
        return OrderFactory::new();
    }

    /**
     * @return BelongsTo<User, $this>
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    /**
     * @return HasMany<OrderItem, $this>
     */
    public function items(): HasMany
    {
        return $this->hasMany(OrderItem::class);
    }

    /**
     * @return HasMany<OrderStatusEvent, $this>
     */
    public function statusEvents(): HasMany
    {
        return $this->hasMany(OrderStatusEvent::class)->orderBy('position');
    }

    /**
     * @return array<string, string>
     */
    #[\Override]
    protected function casts(): array
    {
        return [
            'status' => OrderStatus::class,
            'payment_status' => PaymentStatus::class,
            'delivery_method' => DeliveryMethod::class,
            'subtotal_cents' => 'int',
            'delivery_cents' => 'int',
            'tax_cents' => 'int',
            'total_cents' => 'int',
            'placed_at' => 'datetime',
        ];
    }
}
