<?php

declare(strict_types = 1);

namespace App\Models\Commerce;

enum CheckoutSessionStatus: string
{
    case Completed = 'completed';
    case Failed    = 'failed';
}
