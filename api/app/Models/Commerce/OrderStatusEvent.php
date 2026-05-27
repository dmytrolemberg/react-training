<?php

declare(strict_types = 1);

namespace App\Models\Commerce;

use Illuminate\Support\Carbon;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Database\Factories\Commerce\OrderStatusEventFactory;

/**
 * @property int $id
 * @property int $order_id
 * @property OrderStatus $status
 * @property string $label
 * @property string|null $description
 * @property Carbon|null $occurred_at
 * @property int $position
 * @property Order $order
 */
#[Fillable(['order_id', 'status', 'label', 'description', 'occurred_at', 'position'])]
class OrderStatusEvent extends Model
{
    /** @use HasFactory<OrderStatusEventFactory> */
    use HasFactory;

    protected static function newFactory(): OrderStatusEventFactory
    {
        return OrderStatusEventFactory::new();
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
            'status' => OrderStatus::class,
            'occurred_at' => 'datetime',
            'position' => 'int',
        ];
    }
}
