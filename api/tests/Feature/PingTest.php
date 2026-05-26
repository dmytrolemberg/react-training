<?php

declare(strict_types = 1);

namespace Tests\Feature;

use Tests\TestCase;

class PingTest extends TestCase
{
    public function testPingEndpointReturnsOk(): void
    {
        $this->getJson('/api/ping')
            ->assertOk()
            ->assertExactJson(['status' => 'ok']);
    }
}
