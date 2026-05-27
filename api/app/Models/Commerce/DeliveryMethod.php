<?php

declare(strict_types = 1);

namespace App\Models\Commerce;

enum DeliveryMethod: string
{
    case Standard = 'standard';
    case Express = 'express';
    case Pickup = 'pickup';

    public function label(): string
    {
        return match ($this) {
            self::Standard => 'Standard delivery',
            self::Express => 'Express delivery',
            self::Pickup => 'Pickup point',
        };
    }
}
