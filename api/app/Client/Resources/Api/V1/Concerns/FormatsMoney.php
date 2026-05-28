<?php

declare(strict_types = 1);

namespace App\Client\Resources\Api\V1\Concerns;

trait FormatsMoney
{
    /**
     * @return array{cents: int, currency: string, formatted: string}
     */
    private function money(int $cents): array
    {
        $configuredCurrency = config('app.currency', 'EUR');
        $currency = is_string($configuredCurrency) ? $configuredCurrency : 'EUR';
        $amount = $cents / 100;
        $formatted = number_format($amount, 2) . ' ' . $currency;

        return [
            'cents' => $cents,
            'currency' => $currency,
            'formatted' => $formatted,
        ];
    }
}
