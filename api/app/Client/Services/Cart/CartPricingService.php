<?php

declare(strict_types = 1);

namespace App\Client\Services\Cart;

use App\Models\Commerce\Cart;
use App\Models\Commerce\DeliveryMethod;
use App\Admin\Services\ShopSettingsService;

class CartPricingService
{
    public function __construct(private readonly ShopSettingsService $settings) {}

    /**
     * @return array{subtotal_cents: int, delivery_cents: int, tax_cents: int, total_cents: int, item_count: int}
     */
    public function summarize(Cart $cart, ?DeliveryMethod $deliveryMethod = null): array
    {
        $cart->loadMissing('items');

        $subtotal = 0;
        $itemCount = 0;

        foreach ($cart->items as $item) {
            $subtotal += $item->lineTotalCents();
            $itemCount += $item->quantity;
        }

        $delivery = $deliveryMethod instanceof DeliveryMethod
            ? $this->deliveryCents($deliveryMethod, $subtotal)
            : 0;
        $tax = (int) round($subtotal * $this->settings->taxRate());

        return [
            'subtotal_cents' => $subtotal,
            'delivery_cents' => $delivery,
            'tax_cents' => $tax,
            'total_cents' => $subtotal + $delivery + $tax,
            'item_count' => $itemCount,
        ];
    }

    public function deliveryCents(DeliveryMethod $method, int $subtotalCents): int
    {
        return $this->settings->deliveryCents($method, $subtotalCents);
    }
}
