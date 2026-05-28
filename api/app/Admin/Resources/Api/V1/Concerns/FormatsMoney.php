<?php

declare(strict_types = 1);

namespace App\Admin\Resources\Api\V1\Concerns;

use App\Admin\Services\ShopSettingsService;

trait FormatsMoney
{
    /**
     * @return array{cents: int, currency: string, formatted: string}
     */
    private function money(int $cents): array
    {
        $currency = resolve(ShopSettingsService::class)->currency();

        return [
            'cents' => $cents,
            'currency' => $currency,
            'formatted' => number_format($cents / 100, 2) . ' ' . $currency,
        ];
    }
}
