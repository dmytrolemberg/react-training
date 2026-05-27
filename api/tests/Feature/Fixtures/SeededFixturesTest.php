<?php

declare(strict_types = 1);

namespace Tests\Feature\Fixtures;

use Tests\TestCase;

class SeededFixturesTest extends TestCase
{
    public function testSharedSeedersCreateDeterministicUsers(): void
    {
        $this->assertDatabaseHas('users', [
            'email' => 'user@example.com',
            'role' => 'user',
        ]);

        $this->assertDatabaseHas('users', [
            'email' => 'admin@example.com',
            'role' => 'admin',
        ]);
    }
}
