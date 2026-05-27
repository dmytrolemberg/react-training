<?php

declare(strict_types = 1);

namespace Tests\Feature\Admin\Api\V1;

use Tests\TestCase;
use Illuminate\Support\Facades\Auth;

class AuthTest extends TestCase
{
    public function testRegularUserCanLogInThroughAdminApiButCannotAccessAdminResources(): void
    {
        $this->withHeader('Origin', 'http://localhost:8003')
            ->postJson('/admin/api/v1/auth/login', [
                'email' => 'user@example.com',
                'password' => 'password',
            ])
            ->assertOk()
            ->assertJsonPath('data.email', 'user@example.com')
            ->assertJsonPath('data.role', 'user');

        Auth::forgetGuards();

        $this->withHeader('Origin', 'http://localhost:8003')
            ->getJson('/admin/api/v1/auth/user')
            ->assertOk()
            ->assertJsonPath('data.email', 'user@example.com')
            ->assertJsonPath('data.role', 'user');

        $this->withHeader('Origin', 'http://localhost:8003')
            ->getJson('/admin/api/v1/ping')
            ->assertForbidden();
    }

    public function testAdminCanLogInThroughAdminApiAndAccessAdminResources(): void
    {
        $this->withHeader('Origin', 'http://localhost:8003')
            ->postJson('/admin/api/v1/auth/login', [
                'email' => 'admin@example.com',
                'password' => 'password',
            ])
            ->assertOk()
            ->assertJsonPath('data.email', 'admin@example.com')
            ->assertJsonPath('data.role', 'admin');

        Auth::forgetGuards();

        $this->withHeader('Origin', 'http://localhost:8003')
            ->getJson('/admin/api/v1/ping')
            ->assertOk()
            ->assertExactJson([
                'status' => 'ok',
                'section' => 'admin',
                'version' => 'v1',
            ]);
    }
}
