<?php

declare(strict_types = 1);

namespace App\Client\Services\Checkout;

use App\Models\Commerce\Order;

class OrderNumberGenerator
{
    public function next(): string
    {
        return (string) (1049 + Order::query()->count());
    }
}
