<?php

declare(strict_types = 1);

namespace Tests\Feature\Admin\Api\V1;

use Illuminate\Support\Facades\Auth;
use PHPUnit\Framework\Attributes\DataProvider;

class AuthTest extends AdminApiTestCase
{
    #[DataProvider('protectedAdminEndpoints')]
    public function testAdminProtectedEndpointsRequireAuthentication(string $method, string $uri): void
    {
        $this->json($method, $uri)
            ->assertUnauthorized();
    }

    public function testRegularUserCannotLogInThroughAdminApi(): void
    {
        $this->withHeader('Origin', 'http://localhost:8003')
            ->postJson('/admin/api/v1/auth/login', [
                'email' => 'user@example.com',
                'password' => 'password',
            ])
            ->assertUnprocessable()
            ->assertJsonValidationErrors(['email']);

        $this->assertGuest();
    }

    public function testRegularAuthenticatedUserCannotAccessAdminResources(): void
    {
        $this->actingAs($this->user())
            ->getJson('/admin/api/v1/ping')
            ->assertForbidden();
    }

    public function testAdminCanLogInThroughAdminApiAndAccessAdminResources(): void
    {
        $this->withHeader('Origin', 'http://localhost:8003')
            ->postJson('/admin/api/v1/auth/login', [
                'email' => 'admin@example.com',
                'password' => 'password',
            ])
            ->assertOk()
            ->assertJsonPath('data.email', 'admin@example.com')
            ->assertJsonPath('data.full_name', 'Test Admin')
            ->assertJsonPath('data.role', 'admin');

        Auth::forgetGuards();

        $this->withHeader('Origin', 'http://localhost:8003')
            ->getJson('/admin/api/v1/ping')
            ->assertOk()
            ->assertExactJson([
                'status' => 'ok',
                'section' => 'admin',
                'version' => 'v1',
            ]);
    }

    public function testInvalidAdminCredentialsFailValidation(): void
    {
        $this->withHeader('Origin', 'http://localhost:8003')
            ->postJson('/admin/api/v1/auth/login', [
                'email' => 'admin@example.com',
                'password' => 'wrong-password',
            ])
            ->assertUnprocessable()
            ->assertJsonValidationErrors(['email']);

        $this->assertGuest();
    }

    public function testAdminLogoutInvalidatesSession(): void
    {
        $this->withHeader('Origin', 'http://localhost:8003')
            ->postJson('/admin/api/v1/auth/login', [
                'email' => 'admin@example.com',
                'password' => 'password',
            ])
            ->assertOk();

        $this->withHeader('Origin', 'http://localhost:8003')
            ->postJson('/admin/api/v1/auth/logout')
            ->assertNoContent();

        Auth::forgetGuards();

        $this->withHeader('Origin', 'http://localhost:8003')
            ->getJson('/admin/api/v1/auth/user')
            ->assertUnauthorized();
    }

    /**
     * @return array<string, array{0: string, 1: string}>
     */
    public static function protectedAdminEndpoints(): array
    {
        return [
            'auth logout' => ['POST', '/admin/api/v1/auth/logout'],
            'auth user' => ['GET', '/admin/api/v1/auth/user'],
            'admin ping' => ['GET', '/admin/api/v1/ping'],
            'dashboard' => ['GET', '/admin/api/v1/dashboard'],
            'products' => ['GET', '/admin/api/v1/products'],
            'orders' => ['GET', '/admin/api/v1/orders'],
            'settings' => ['GET', '/admin/api/v1/settings'],
        ];
    }
}
