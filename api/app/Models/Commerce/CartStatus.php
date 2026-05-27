<?php

declare(strict_types = 1);

namespace App\Models\Commerce;

enum CartStatus: string
{
    case Active = 'active';
    case CheckedOut = 'checked_out';
}
