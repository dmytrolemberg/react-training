<?php

declare(strict_types = 1);

namespace Tests\Feature\Admin\Api\V1\Settings;

use Tests\Feature\Admin\Api\V1\AdminApiTestCase;

class SettingsTest extends AdminApiTestCase
{
    public function testAdminCanReadAndUpdateSettingsWithoutCouponFields(): void
    {
        $this->actingAsAdmin()
            ->getJson('/admin/api/v1/settings')
            ->assertOk()
            ->assertJsonPath('data.general.currency', 'EUR')
            ->assertJsonMissingPath('data.checkout.allow_coupons');

        $this->actingAsAdmin()
            ->patchJson('/admin/api/v1/settings', [
                'general' => ['currency' => 'USD'],
                'tax' => ['rate' => 0.1],
                'shipping' => ['standard_delivery_cents' => 700],
            ])
            ->assertOk()
            ->assertJsonPath('data.general.currency', 'USD')
            ->assertJsonPath('data.tax.rate', 0.1)
            ->assertJsonPath('data.shipping.standard_delivery_cents', 700)
            ->assertJsonMissingPath('data.checkout.allow_coupons');
    }

    public function testSettingsValidationRejectsInvalidCurrencyAndTaxRate(): void
    {
        $this->actingAsAdmin()
            ->patchJson('/admin/api/v1/settings', [
                'general' => ['currency' => 'BTC'],
                'tax' => ['rate' => 2],
            ])
            ->assertUnprocessable()
            ->assertJsonValidationErrors(['general.currency', 'tax.rate']);
    }
}
