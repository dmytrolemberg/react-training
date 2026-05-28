<?php

declare(strict_types = 1);

namespace Tests\Feature\Admin\Api\V1\Customer;

use Tests\Feature\Admin\Api\V1\AdminApiTestCase;

class CustomerManagementTest extends AdminApiTestCase
{
    public function testAdminCanManageCustomers(): void
    {
        $customer = $this->actingAsAdmin()
            ->postJson('/admin/api/v1/customers', [
                'first_name' => 'Ada',
                'last_name' => 'Customer',
                'email' => 'ada.customer@example.com',
                'password' => 'password123',
                'phone' => '+380000000001',
            ])
            ->assertCreated()
            ->assertJsonPath('data.email', 'ada.customer@example.com')
            ->assertJsonPath('data.role', 'user');

        $this->actingAsAdmin()
            ->getJson('/admin/api/v1/customers?search=ada.customer')
            ->assertOk()
            ->assertJsonPath('data.0.email', 'ada.customer@example.com');

        $this->actingAsAdmin()
            ->patchJson('/admin/api/v1/customers/' . $customer->json('data.id'), ['city' => 'Lviv'])
            ->assertOk()
            ->assertJsonPath('data.city', 'Lviv');

        $this->actingAsAdmin()
            ->deleteJson('/admin/api/v1/customers/' . $customer->json('data.id'))
            ->assertNoContent();

        $this->assertDatabaseMissing('users', ['email' => 'ada.customer@example.com']);
    }

    public function testCustomerValidationRejectsDuplicateEmail(): void
    {
        $this->actingAsAdmin()
            ->postJson('/admin/api/v1/customers', [
                'first_name' => 'Duplicate',
                'last_name' => 'User',
                'email' => 'user@example.com',
                'password' => 'password123',
            ])
            ->assertUnprocessable()
            ->assertJsonValidationErrors(['email']);
    }
}
