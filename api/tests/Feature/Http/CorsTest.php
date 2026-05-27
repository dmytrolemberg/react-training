<?php

declare(strict_types = 1);

namespace Tests\Feature\Http;

use Tests\TestCase;
use App\Models\User\User;

class CorsTest extends TestCase
{
    public function testClientOriginReceivesCorsHeadersForClientApi(): void
    {
        $this->withHeader('Origin', 'http://localhost:8002')
            ->get('/api/v1/ping')
            ->assertOk()
            ->assertHeader('Access-Control-Allow-Origin', 'http://localhost:8002')
            ->assertHeader('Access-Control-Allow-Credentials', 'true');
    }

    public function testAdminOriginReceivesCorsHeadersForAdminApi(): void
    {
        $admin = User::query()->where('email', 'admin@example.com')->firstOrFail();

        $this->actingAs($admin)
            ->withHeader('Origin', 'http://localhost:8003')
            ->get('/admin/api/v1/ping')
            ->assertOk()
            ->assertHeader('Access-Control-Allow-Origin', 'http://localhost:8003')
            ->assertHeader('Access-Control-Allow-Credentials', 'true');
    }

    public function testSanctumCsrfEndpointSupportsConfiguredSpaOrigins(): void
    {
        $this->withHeader('Origin', 'http://localhost:8002')
            ->get('/sanctum/csrf-cookie')
            ->assertNoContent()
            ->assertHeader('Access-Control-Allow-Origin', 'http://localhost:8002')
            ->assertHeader('Access-Control-Allow-Credentials', 'true')
            ->assertCookie('XSRF-TOKEN');
    }
}
