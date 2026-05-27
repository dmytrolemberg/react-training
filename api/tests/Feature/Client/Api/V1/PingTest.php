<?php

declare(strict_types = 1);

namespace Tests\Feature\Client\Api\V1;

use Tests\TestCase;

class PingTest extends TestCase
{
    public function testClientPingEndpointReturnsOk(): void
    {
        $this->getJson('/api/v1/ping')
            ->assertOk()
            ->assertExactJson([
                'status' => 'ok',
                'section' => 'client',
                'version' => 'v1',
            ]);
    }
}
