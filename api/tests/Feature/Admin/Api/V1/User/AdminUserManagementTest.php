<?php

declare(strict_types = 1);

namespace Tests\Feature\Admin\Api\V1\User;

use App\Models\User\User;
use Tests\Feature\Admin\Api\V1\AdminApiTestCase;

class AdminUserManagementTest extends AdminApiTestCase
{
    public function testAdminCanManageAdminUsers(): void
    {
        $admin = $this->actingAsAdmin()
            ->postJson('/admin/api/v1/admin-users', [
                'first_name' => 'Second',
                'last_name' => 'Admin',
                'email' => 'second.admin@example.com',
                'password' => 'password123',
            ])
            ->assertCreated()
            ->assertJsonPath('data.role', 'admin');

        $this->actingAsAdmin()
            ->patchJson('/admin/api/v1/admin-users/' . $admin->json('data.id'), ['first_name' => 'Renamed'])
            ->assertOk()
            ->assertJsonPath('data.first_name', 'Renamed');

        $this->actingAsAdmin()
            ->deleteJson('/admin/api/v1/admin-users/' . $admin->json('data.id'))
            ->assertNoContent();

        $this->assertDatabaseMissing('users', ['email' => 'second.admin@example.com']);
    }

    public function testAdminCannotDeleteCurrentAdmin(): void
    {
        User::factory()->admin()->create();

        $this->actingAsAdmin()
            ->deleteJson('/admin/api/v1/admin-users/' . $this->admin()->id)
            ->assertUnprocessable()
            ->assertJsonValidationErrors(['admin_user']);
    }

    public function testAdminCannotDeleteLastRemainingAdmin(): void
    {
        $externalAdmin = User::factory()->admin()->make(['id' => 999999]);

        $this->actingAs($externalAdmin)
            ->deleteJson('/admin/api/v1/admin-users/' . $this->admin()->id)
            ->assertUnprocessable()
            ->assertJsonValidationErrors(['admin_user']);
    }

    public function testAdminUserValidationRejectsDuplicateEmail(): void
    {
        $this->actingAsAdmin()
            ->postJson('/admin/api/v1/admin-users', [
                'first_name' => 'Duplicate',
                'last_name' => 'Admin',
                'email' => 'admin@example.com',
                'password' => 'password123',
            ])
            ->assertUnprocessable()
            ->assertJsonValidationErrors(['email']);
    }
}
