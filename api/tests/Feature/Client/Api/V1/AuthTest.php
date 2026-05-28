<?php

declare(strict_types = 1);

namespace Tests\Feature\Client\Api\V1;

use Tests\TestCase;
use App\Models\User\User;
use Illuminate\Support\Facades\Auth;
use PHPUnit\Framework\Attributes\DataProvider;

class AuthTest extends TestCase
{
    /**
     * @param array<string, mixed> $payload
     */
    #[DataProvider('protectedClientEndpoints')]
    public function testClientProtectedEndpointsRequireAuthentication(string $method, string $uri, array $payload): void
    {
        $this->json($method, $uri, $payload)
            ->assertUnauthorized();
    }

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
            ->assertJsonPath('data.first_name', 'Dmytro')
            ->assertJsonPath('data.last_name', 'Orikhovskyi')
            ->assertJsonPath('data.full_name', 'Dmytro Orikhovskyi')
            ->assertJsonPath('data.avatar_path', '/images/profiles/dmytro-orikhovskyi.jpg')
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
            ->assertJsonPath('data.first_name', 'Dmytro')
            ->assertJsonPath('data.last_name', 'Orikhovskyi')
            ->assertJsonPath('data.full_name', 'Dmytro Orikhovskyi')
            ->assertJsonPath('data.avatar_path', '/images/profiles/dmytro-orikhovskyi.jpg')
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

    /**
     * @return array<string, array{0: string, 1: string, 2: array<string, mixed>}>
     */
    public static function protectedClientEndpoints(): array
    {
        return [
            'auth logout' => ['POST', '/api/v1/auth/logout', []],
            'auth user' => ['GET', '/api/v1/auth/user', []],
            'cart show' => ['GET', '/api/v1/cart', []],
            'cart add item' => ['POST', '/api/v1/cart/items', ['product_id' => 1, 'quantity' => 1]],
            'cart update item' => ['PATCH', '/api/v1/cart/items/1', ['quantity' => 1]],
            'cart delete item' => ['DELETE', '/api/v1/cart/items/1', []],
            'cart clear' => ['DELETE', '/api/v1/cart', []],
            'checkout options' => ['GET', '/api/v1/checkout/options', []],
            'checkout order' => ['POST', '/api/v1/checkout/orders', []],
            'orders index' => ['GET', '/api/v1/orders', []],
            'order detail' => ['GET', '/api/v1/orders/1048', []],
            'order tracking' => ['GET', '/api/v1/orders/1048/tracking', []],
            'profile show' => ['GET', '/api/v1/profile', []],
            'profile update' => ['PATCH', '/api/v1/profile', ['first_name' => 'Test', 'last_name' => 'User', 'email' => 'test@example.com']],
            'payment methods index' => ['GET', '/api/v1/profile/payment-methods', []],
            'payment methods store' => ['POST', '/api/v1/profile/payment-methods', ['mock_token' => 'pm_mock_visa_1111']],
            'payment methods delete' => ['DELETE', '/api/v1/profile/payment-methods/1', []],
            'wishlist index' => ['GET', '/api/v1/profile/wishlist', []],
            'wishlist store' => ['POST', '/api/v1/profile/wishlist', ['product_id' => 1]],
            'wishlist delete' => ['DELETE', '/api/v1/profile/wishlist/1', []],
            'review store' => ['POST', '/api/v1/reviews', ['product_id' => 1, 'rating' => 5, 'body' => 'Great product.']],
        ];
    }
}
