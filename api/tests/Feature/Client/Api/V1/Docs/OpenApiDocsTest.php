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

        $this->getJson('/docs/client.json')
            ->assertOk()
            ->assertJsonPath('info.title', 'North Shop Client API')
            ->assertJsonStructure([
                'openapi',
                'paths' => [
                    '/catalog/products',
                    '/cart',
                    '/checkout/orders',
                ],
            ]);
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
                ],
            ]);
    }

    public function testDefaultOpenApiDocumentIsDisabled(): void
    {
        $this->getJson('/docs/api.json')
            ->assertNotFound();
    }
}
