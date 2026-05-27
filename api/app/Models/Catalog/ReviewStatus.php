<?php

declare(strict_types = 1);

namespace App\Models\Catalog;

enum ReviewStatus: string
{
    case Pending = 'pending';
    case Approved = 'approved';
    case Rejected = 'rejected';
}
