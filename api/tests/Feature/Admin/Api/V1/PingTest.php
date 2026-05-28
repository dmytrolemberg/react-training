<?php

declare(strict_types = 1);

namespace Tests\Feature\Admin\Api\V1;

use App\Models\User\User;

class PingTest extends AdminApiTestCase
{
    public function testGuestCannotAccessAdminPingEndpoint(): void
    {
        $this->getJson('/admin/api/v1/ping')
            ->assertUnauthorized();
    }

    public function testRegularUserCannotAccessAdminPingEndpoint(): void
    {
        $user = User::query()->where('email', 'user@example.com')->firstOrFail();

        $this->actingAs($user)
            ->getJson('/admin/api/v1/ping')
            ->assertForbidden();
    }

    public function testAdminCanAccessAdminPingEndpoint(): void
    {
        $admin = User::query()->where('email', 'admin@example.com')->firstOrFail();

        $this->actingAs($admin)
            ->getJson('/admin/api/v1/ping')
            ->assertOk()
            ->assertExactJson([
                'status' => 'ok',
                'section' => 'admin',
                'version' => 'v1',
            ]);
    }
}
