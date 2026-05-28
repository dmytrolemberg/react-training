<?php

declare(strict_types = 1);

namespace App\Admin\Services;

use App\Models\Shop\ShopSetting;
use Illuminate\Support\Facades\Schema;
use App\Models\Commerce\DeliveryMethod;

class ShopSettingsService
{
    /**
     * @var array<string, array<string, mixed>>
     */
    public const DEFAULTS = [
        'general' => [
            'shop_name' => 'North Shop',
            'currency' => 'EUR',
        ],
        'checkout' => [
            'guest_checkout' => false,
            'require_phone' => false,
            'send_order_confirmation' => true,
        ],
        'shipping' => [
            'standard_delivery_cents' => 500,
            'express_delivery_cents' => 1200,
            'pickup_delivery_cents' => 0,
            'free_standard_delivery_threshold_cents' => 10000,
        ],
        'tax' => [
            'rate' => 0.08,
        ],
        'search' => [
            'products_per_page' => 12,
        ],
        'reviews' => [
            'require_moderation' => true,
        ],
        'notifications' => [
            'order_confirmation' => true,
        ],
    ];

    /**
     * @return array<string, array<string, mixed>>
     */
    public function all(): array
    {
        /** @var array<string, array<string, mixed>> $settings */
        $settings = [];
        foreach (self::DEFAULTS as $key => $value) {
            $settings[$key] = $value;
        }

        if (! Schema::hasTable('shop_settings')) {
            return $settings;
        }

        ShopSetting::query()
            ->whereIn('key', array_keys(self::DEFAULTS))
            ->get()
            ->each(function (ShopSetting $setting) use (&$settings): void {
                if (! is_array($setting->value)) {
                    return;
                }

                /** @var array<string, mixed> $value */
                $value = $setting->value;
                $settings[$setting->key] = array_replace($settings[$setting->key] ?? [], $value);
            });

        return $settings;
    }

    /**
     * @param array<string, mixed> $settings
     * @return array<string, array<string, mixed>>
     */
    public function update(array $settings): array
    {
        foreach ($settings as $key => $value) {
            if (! array_key_exists($key, self::DEFAULTS)) {
                continue;
            }

            if (! is_array($value)) {
                continue;
            }

            $current = $this->all()[$key] ?? self::DEFAULTS[$key];
            ShopSetting::query()->updateOrCreate(
                ['key' => $key],
                ['value' => array_replace($current, $value)],
            );
        }

        return $this->all();
    }

    public function currency(): string
    {
        $currency = $this->all()['general']['currency'] ?? 'EUR';

        return is_string($currency) ? $currency : 'EUR';
    }

    public function taxRate(): float
    {
        $rate = $this->all()['tax']['rate'] ?? 0.08;

        return is_numeric($rate) ? (float) $rate : 0.08;
    }

    public function deliveryCents(DeliveryMethod $method, int $subtotalCents): int
    {
        $shipping = $this->all()['shipping'];

        return match ($method) {
            DeliveryMethod::Pickup => $this->intSetting($shipping['pickup_delivery_cents'] ?? 0),
            DeliveryMethod::Express => $this->intSetting($shipping['express_delivery_cents'] ?? 1200),
            DeliveryMethod::Standard => $subtotalCents >= $this->intSetting($shipping['free_standard_delivery_threshold_cents'] ?? 10000)
                ? 0
                : $this->intSetting($shipping['standard_delivery_cents'] ?? 500),
        };
    }

    private function intSetting(mixed $value): int
    {
        return is_numeric($value) ? (int) $value : 0;
    }
}
