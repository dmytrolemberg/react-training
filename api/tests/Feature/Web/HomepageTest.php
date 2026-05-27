<?php

declare(strict_types = 1);

namespace Tests\Feature\Web;

use Tests\TestCase;

class HomepageTest extends TestCase
{
    public function testHomepageReturnsSuccessfulResponse(): void
    {
        $this->get('/')
            ->assertOk();
    }
}
