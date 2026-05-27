<?php

declare(strict_types = 1);

namespace App\Models\Commerce;

enum OrderStatus: string
{
    case Processing = 'processing';
    case Shipped = 'shipped';
    case Delivered = 'delivered';
    case Cancelled = 'cancelled';
}
