<?php

namespace Tests\Feature;

use Tests\TestCase;

class PingTest extends TestCase
{
    public function test_ping_endpoint_returns_ok(): void
    {
        $this->getJson('/api/ping')
            ->assertOk()
            ->assertExactJson(['status' => 'ok']);
    }
}
