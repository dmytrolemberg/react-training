<?php

declare(strict_types = 1);

namespace App\Client\Resources\Api\V1\Concerns;

trait FormatsMoney
{
    /**
     * @return array{cents: int, currency: string, formatted: string}
     */
    private function money(int $cents, string $currency): array
    {
        $amount = $cents / 100;
        $formatted = $currency === 'USD'
            ? '$' . number_format($amount, 2)
            : number_format($amount, 2) . ' ' . $currency;

        return [
            'cents' => $cents,
            'currency' => $currency,
            'formatted' => $formatted,
        ];
    }
}
