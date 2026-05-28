<?php

declare(strict_types = 1);

namespace Tests\Feature\Admin\Api\V1\Commerce;

use App\Models\Catalog\Product;
use App\Models\Commerce\CheckoutSession;
use App\Models\Commerce\CheckoutSessionStatus;
use Tests\Feature\Admin\Api\V1\AdminApiTestCase;

class CheckoutSessionRecordingTest extends AdminApiTestCase
{
    public function testSuccessfulCheckoutCreatesCompletedCheckoutSession(): void
    {
        $this->actingAs($this->user())
            ->postJson('/api/v1/checkout/orders', $this->checkoutPayload())
            ->assertCreated();

        $this->assertDatabaseHas('checkout_sessions', [
            'user_id' => $this->user()->id,
            'status' => CheckoutSessionStatus::Completed->value,
            'contact_email' => 'dmytro@example.com',
            'payment_method_type' => 'mock_card',
        ]);

        $session = CheckoutSession::query()->latest()->firstOrFail();
        $this->assertArrayNotHasKey('card_number', $session->payload ?? []);

        $this->actingAsAdmin()
            ->getJson('/admin/api/v1/checkout-sessions/' . $session->id)
            ->assertOk()
            ->assertJsonPath('data.status', 'completed')
            ->assertJsonPath('data.order.id', $session->order_id);
    }

    public function testFailedCheckoutCreatesFailedCheckoutSession(): void
    {
        Product::query()
            ->where('slug', 'everyday-carry-pack')
            ->firstOrFail()
            ->forceFill(['stock_quantity' => 0])
            ->save();

        $this->actingAs($this->user())
            ->postJson('/api/v1/checkout/orders', $this->checkoutPayload())
            ->assertUnprocessable()
            ->assertJsonValidationErrors(['quantity']);

        $this->assertDatabaseHas('checkout_sessions', [
            'user_id' => $this->user()->id,
            'status' => CheckoutSessionStatus::Failed->value,
            'failure_stage' => 'checkout',
        ]);

        $session = CheckoutSession::query()->latest()->firstOrFail();
        $this->assertNull($session->order_id);
        $this->assertArrayNotHasKey('card_number', $session->payload ?? []);
    }

    /**
     * @return array<string, mixed>
     */
    private function checkoutPayload(): array
    {
        return [
            'contact' => [
                'email' => 'dmytro@example.com',
                'phone' => '+380000000000',
            ],
            'shipping_address' => [
                'first_name' => 'Dmytro',
                'last_name' => 'Orikhovskyi',
                'country' => 'Ukraine',
                'city' => 'Kyiv',
                'postal_code' => '01001',
                'address_line' => 'Street address placeholder',
            ],
            'delivery_method' => 'standard',
            'payment_method_type' => 'mock_card',
        ];
    }
}
