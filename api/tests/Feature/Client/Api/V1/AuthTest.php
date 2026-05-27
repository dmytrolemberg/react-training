<?php

declare(strict_types = 1);

namespace Tests\Feature\Client\Api\V1;

use Tests\TestCase;
use App\Models\User\User;
use Illuminate\Support\Facades\Auth;

class AuthTest extends TestCase
{
    public function testSeededUserCanLogIn(): void
    {
        $user = User::query()->where('email', 'user@example.com')->firstOrFail();

        $this->withHeader('Origin', 'http://localhost:8002')
            ->postJson('/api/v1/auth/login', [
                'email' => 'user@example.com',
                'password' => 'password',
            ])
            ->assertOk()
            ->assertJsonPath('data.email', 'user@example.com')
            ->assertJsonPath('data.role', 'user');

        $this->assertAuthenticatedAs($user);
    }

    public function testInvalidCredentialsFailValidation(): void
    {
        $this->withHeader('Origin', 'http://localhost:8002')
            ->postJson('/api/v1/auth/login', [
                'email' => 'user@example.com',
                'password' => 'wrong-password',
            ])
            ->assertUnprocessable()
            ->assertJsonValidationErrors(['email']);

        $this->assertGuest();
    }

    public function testAuthenticatedUserEndpointReturnsCurrentUser(): void
    {
        $user = User::query()->where('email', 'user@example.com')->firstOrFail();

        $this->actingAs($user)
            ->withHeader('Origin', 'http://localhost:8002')
            ->getJson('/api/v1/auth/user')
            ->assertOk()
            ->assertJsonPath('data.id', $user->getKey())
            ->assertJsonPath('data.email', 'user@example.com')
            ->assertJsonPath('data.role', 'user');
    }

    public function testLogoutInvalidatesAuthenticatedSession(): void
    {
        $this->withHeader('Origin', 'http://localhost:8002')
            ->postJson('/api/v1/auth/login', [
                'email' => 'user@example.com',
                'password' => 'password',
            ])
            ->assertOk();

        $this->withHeader('Origin', 'http://localhost:8002')
            ->postJson('/api/v1/auth/logout')
            ->assertNoContent();

        Auth::forgetGuards();

        $this->withHeader('Origin', 'http://localhost:8002')
            ->getJson('/api/v1/auth/user')
            ->assertUnauthorized();
    }
}
