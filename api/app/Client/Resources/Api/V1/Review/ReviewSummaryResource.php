<?php

declare(strict_types = 1);

namespace App\Client\Resources\Api\V1\Review;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class ReviewSummaryResource extends JsonResource
{
    /**
     * @return array<string, mixed>
     */
    #[\Override]
    public function toArray(Request $request): array
    {
        $summary = is_array($this->resource) ? $this->resource : [];

        return [
            'average' => $summary['average'] ?? 0.0,
            'total' => $summary['total'] ?? 0,
            'breakdown' => $summary['breakdown'] ?? [],
        ];
    }
}
