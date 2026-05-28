<?php

declare(strict_types = 1);

namespace Tests\Feature\Client\Api\V1\Docs;

use Tests\TestCase;

class OpenApiDocsTest extends TestCase
{
    public function testClientOpenApiDocumentationIsAvailable(): void
    {
        $this->get('/docs/client')
            ->assertOk();

        $response = $this->getJson('/docs/client.json')
            ->assertOk()
            ->assertJsonPath('info.title', 'North Shop Client API')
            ->assertJsonStructure([
                'openapi',
                'paths' => [
                    '/catalog/products',
                    '/cart',
                    '/profile',
                    '/checkout/orders',
                ],
            ]);

        $paths = $response->json('paths');
        $this->assertIsArray($paths);
        $this->assertArrayHasKey('/profile', $paths);
        $this->assertArrayNotHasKey('/profile/addresses', $paths);
    }

    public function testAdminOpenApiDocumentationIsAvailable(): void
    {
        $this->get('/docs/admin')
            ->assertOk();

        $this->getJson('/docs/admin.json')
            ->assertOk()
            ->assertJsonPath('info.title', 'North Shop Admin API')
            ->assertJsonStructure([
                'openapi',
                'paths' => [
                    '/auth/login',
                    '/ping',
                    '/dashboard',
                    '/products',
                    '/orders',
                    '/checkout-sessions',
                    '/settings',
                    '/admin-users',
                ],
            ]);

        $paths = $this->getJson('/docs/admin.json')->json('paths');
        $this->assertIsArray($paths);
        $this->assertArrayNotHasKey('/coupons', $paths);
        $this->assertArrayNotHasKey('/coupons/{coupon}', $paths);
    }

    public function testDefaultOpenApiDocumentIsDisabled(): void
    {
        $this->getJson('/docs/api.json')
            ->assertNotFound();
    }
}
