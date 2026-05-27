<?php

declare(strict_types = 1);

namespace Tests\Feature\Client\Api\V1\Order;

use Tests\TestCase;
use App\Models\User\User;
use App\Models\Commerce\Order;

class OrderTest extends TestCase
{
    public function testOrdersRequireAuthentication(): void
    {
        $this->getJson('/api/v1/orders')->assertUnauthorized();
    }

    public function testOrdersCanBeListedAndFilteredByStatus(): void
    {
        $this->actingAs($this->user())
            ->getJson('/api/v1/orders?status=processing')
            ->assertOk()
            ->assertJsonPath('data.0.number', '1048')
            ->assertJsonPath('data.0.status', 'processing');
    }

    public function testInvalidOrderStatusFailsValidation(): void
    {
        $this->actingAs($this->user())
            ->getJson('/api/v1/orders?status=lost')
            ->assertUnprocessable()
            ->assertJsonValidationErrors(['status']);
    }

    public function testInvalidOrderPaginationFailsValidation(): void
    {
        $this->actingAs($this->user())
            ->getJson('/api/v1/orders?per_page=100')
            ->assertUnprocessable()
            ->assertJsonValidationErrors(['per_page']);
    }

    public function testOrderDetailReturnsOnlyOwnOrder(): void
    {
        $this->actingAs($this->user())
            ->getJson('/api/v1/orders/1048')
            ->assertOk()
            ->assertJsonPath('data.number', '1048')
            ->assertJsonPath('data.items.0.name', 'Everyday Carry Pack');
    }

    public function testOtherUsersOrderIsNotVisible(): void
    {
        $other = User::factory()->create();
        $order = Order::factory()->for($other)->create(['number' => '9999']);

        $this->actingAs($this->user())
            ->getJson('/api/v1/orders/' . $order->number)
            ->assertNotFound();
    }

    public function testUnknownOrderNumberIsNotFound(): void
    {
        $this->actingAs($this->user())
            ->getJson('/api/v1/orders/does-not-exist')
            ->assertNotFound();

        $this->actingAs($this->user())
            ->getJson('/api/v1/orders/does-not-exist/tracking')
            ->assertNotFound();
    }

    public function testTrackingEndpointReturnsTimeline(): void
    {
        $this->actingAs($this->user())
            ->getJson('/api/v1/orders/1048/tracking')
            ->assertOk()
            ->assertJsonPath('data.order_number', '1048')
            ->assertJsonPath('data.timeline.0.label', 'Order placed');
    }

    private function user(): User
    {
        return User::query()->where('email', 'user@example.com')->firstOrFail();
    }
}
