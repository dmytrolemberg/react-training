<?php

declare(strict_types = 1);

namespace Tests\Feature\Admin\Api\V1\Commerce;

use App\Models\User\User;
use App\Models\Commerce\Cart;
use App\Models\Commerce\Order;
use App\Models\Commerce\OrderStatus;
use App\Models\Commerce\PaymentStatus;
use App\Models\Commerce\CheckoutSession;
use Tests\Feature\Admin\Api\V1\AdminApiTestCase;

class CommerceManagementTest extends AdminApiTestCase
{
    public function testAdminCanReadDashboardAndManageOrders(): void
    {
        $order = Order::query()->where('number', '1048')->firstOrFail();

        $this->actingAsAdmin()
            ->getJson('/admin/api/v1/dashboard')
            ->assertOk()
            ->assertJsonStructure(['data' => ['totals', 'recent_orders', 'low_stock_products']]);

        $this->actingAsAdmin()
            ->getJson('/admin/api/v1/orders?status=processing')
            ->assertOk()
            ->assertJsonPath('data.0.number', '1048');

        $this->actingAsAdmin()
            ->getJson('/admin/api/v1/orders/' . $order->id)
            ->assertOk()
            ->assertJsonPath('data.number', '1048');

        $this->actingAsAdmin()
            ->patchJson('/admin/api/v1/orders/' . $order->id . '/status', [
                'status' => OrderStatus::Shipped->value,
                'label' => 'Shipped',
                'description' => 'Package left the warehouse.',
            ])
            ->assertOk()
            ->assertJsonPath('data.status', 'shipped');

        $this->actingAsAdmin()
            ->patchJson('/admin/api/v1/orders/' . $order->id . '/payment-status', [
                'payment_status' => PaymentStatus::Refunded->value,
            ])
            ->assertOk()
            ->assertJsonPath('data.payment_status', 'refunded');
    }

    public function testAdminCanManageCartsAndCheckoutSessions(): void
    {
        $customer = User::factory()->create();
        $otherCustomer = User::factory()->create();

        $cart = $this->actingAsAdmin()
            ->postJson('/admin/api/v1/carts', ['user_id' => $customer->id])
            ->assertCreated()
            ->assertJsonPath('data.user_id', $customer->id);

        $this->actingAsAdmin()
            ->patchJson('/admin/api/v1/carts/' . $cart->json('data.id'), ['user_id' => $otherCustomer->id])
            ->assertOk()
            ->assertJsonPath('data.user_id', $otherCustomer->id);

        $session = CheckoutSession::factory()->failed()->create(['user_id' => $otherCustomer->id]);

        $this->actingAsAdmin()
            ->getJson('/admin/api/v1/checkout-sessions?status=failed')
            ->assertOk()
            ->assertJsonFragment(['id' => $session->id]);

        $this->actingAsAdmin()
            ->deleteJson('/admin/api/v1/checkout-sessions/' . $session->id)
            ->assertNoContent();

        $this->actingAsAdmin()
            ->deleteJson('/admin/api/v1/carts/' . $cart->json('data.id'))
            ->assertNoContent();

        $this->assertDatabaseMissing('checkout_sessions', ['id' => $session->id]);
        $this->assertDatabaseMissing('carts', ['id' => $cart->json('data.id')]);
    }

    public function testCartValidationRejectsDuplicateUserCart(): void
    {
        $cart = Cart::query()->firstOrFail();

        $this->actingAsAdmin()
            ->postJson('/admin/api/v1/carts', ['user_id' => $cart->user_id])
            ->assertUnprocessable()
            ->assertJsonValidationErrors(['user_id']);
    }
}
