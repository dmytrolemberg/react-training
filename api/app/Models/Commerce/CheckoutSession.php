<?php

declare(strict_types = 1);

namespace App\Models\Commerce;

use App\Models\User\User;
use Illuminate\Support\Carbon;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Database\Factories\Commerce\CheckoutSessionFactory;

/**
 * @property int $id
 * @property int|null $user_id
 * @property int|null $cart_id
 * @property int|null $order_id
 * @property CheckoutSessionStatus $status
 * @property string|null $failure_stage
 * @property string|null $failure_reason
 * @property string|null $contact_email
 * @property string|null $delivery_method
 * @property string|null $payment_method_type
 * @property int $subtotal_cents
 * @property int $delivery_cents
 * @property int $tax_cents
 * @property int $total_cents
 * @property int $item_count
 * @property array<string, mixed>|null $payload
 * @property Carbon|null $completed_at
 * @property Carbon|null $failed_at
 * @property User|null $user
 * @property Cart|null $cart
 * @property Order|null $order
 */
#[Fillable([
    'user_id',
    'cart_id',
    'order_id',
    'status',
    'failure_stage',
    'failure_reason',
    'contact_email',
    'delivery_method',
    'payment_method_type',
    'subtotal_cents',
    'delivery_cents',
    'tax_cents',
    'total_cents',
    'item_count',
    'payload',
    'completed_at',
    'failed_at',
])]
class CheckoutSession extends Model
{
    /** @use HasFactory<CheckoutSessionFactory> */
    use HasFactory;

    protected static function newFactory(): CheckoutSessionFactory
    {
        return CheckoutSessionFactory::new();
    }

    /**
     * @return BelongsTo<User, $this>
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    /**
     * @return BelongsTo<Cart, $this>
     */
    public function cart(): BelongsTo
    {
        return $this->belongsTo(Cart::class);
    }

    /**
     * @return BelongsTo<Order, $this>
     */
    public function order(): BelongsTo
    {
        return $this->belongsTo(Order::class);
    }

    /**
     * @return array<string, string>
     */
    #[\Override]
    protected function casts(): array
    {
        return [
            'status' => CheckoutSessionStatus::class,
            'subtotal_cents' => 'int',
            'delivery_cents' => 'int',
            'tax_cents' => 'int',
            'total_cents' => 'int',
            'item_count' => 'int',
            'payload' => 'array',
            'completed_at' => 'datetime',
            'failed_at' => 'datetime',
        ];
    }
}
