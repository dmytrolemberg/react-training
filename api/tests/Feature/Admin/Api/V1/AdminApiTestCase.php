<?php

declare(strict_types = 1);

namespace Tests\Feature\Admin\Api\V1;

use Tests\TestCase;
use App\Models\User\User;

abstract class AdminApiTestCase extends TestCase
{
    protected function admin(): User
    {
        return User::query()->where('email', 'admin@example.com')->firstOrFail();
    }

    protected function user(): User
    {
        return User::query()->where('email', 'user@example.com')->firstOrFail();
    }

    protected function actingAsAdmin(): static
    {
        return $this->actingAs($this->admin());
    }
}
