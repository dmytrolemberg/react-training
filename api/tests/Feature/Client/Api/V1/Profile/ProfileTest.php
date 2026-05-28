<?php

declare(strict_types = 1);

namespace Tests\Feature\Client\Api\V1\Profile;

use Tests\TestCase;
use App\Models\User\User;
use App\Models\Catalog\Product;
use App\Models\Account\WishlistItem;
use App\Models\Account\PaymentMethod;

class ProfileTest extends TestCase
{
    public function testProfileRequiresAuthentication(): void
    {
        $this->getJson('/api/v1/profile')->assertUnauthorized();
    }

    public function testProfileShowsAccountStatsAndSavedData(): void
    {
        $this->actingAs($this->user())
            ->getJson('/api/v1/profile')
            ->assertOk()
            ->assertJsonPath('data.email', 'user@example.com')
            ->assertJsonPath('data.first_name', 'Dmytro')
            ->assertJsonPath('data.last_name', 'Orikhovskyi')
            ->assertJsonPath('data.full_name', 'Dmytro Orikhovskyi')
            ->assertJsonPath('data.avatar_path', '/images/profiles/dmytro-orikhovskyi.jpg')
            ->assertJsonPath('data.phone', '+380000000000')
            ->assertJsonPath('data.country', 'Ukraine')
            ->assertJsonPath('data.city', 'Kyiv')
            ->assertJsonPath('data.postal_code', '01001')
            ->assertJsonPath('data.address_line', 'Street address placeholder')
            ->assertJsonPath('data.stats.orders_count', 1)
            ->assertJsonPath('data.payment_methods.0.last_four', '4242');
    }

    public function testAddressRoutesAreRemoved(): void
    {
        $this->actingAs($this->user())
            ->getJson('/api/v1/profile/addresses')
            ->assertNotFound();
    }

    public function testProfileCanBeUpdatedWithFlatAddressFieldsAndValidatesUniqueEmail(): void
    {
        $other = User::factory()->create(['email' => 'taken@example.com']);

        $this->actingAs($this->user())
            ->patchJson('/api/v1/profile', [
                'email' => $other->email,
            ])
            ->assertUnprocessable()
            ->assertJsonValidationErrors(['email']);

        $this->actingAs($this->user())
            ->patchJson('/api/v1/profile', [
                'first_name' => 'Updated',
                'last_name' => 'User',
                'email' => 'updated@example.com',
                'avatar_path' => '/images/profiles/updated-user.jpg',
                'phone' => '+380111111111',
                'country' => 'Ukraine',
                'city' => 'Lviv',
                'postal_code' => '79000',
                'address_line' => 'Warehouse address',
            ])
            ->assertOk()
            ->assertJsonPath('data.first_name', 'Updated')
            ->assertJsonPath('data.last_name', 'User')
            ->assertJsonPath('data.full_name', 'Updated User')
            ->assertJsonPath('data.email', 'updated@example.com')
            ->assertJsonPath('data.avatar_path', '/images/profiles/updated-user.jpg')
            ->assertJsonPath('data.phone', '+380111111111')
            ->assertJsonPath('data.city', 'Lviv')
            ->assertJsonPath('data.postal_code', '79000')
            ->assertJsonPath('data.address_line', 'Warehouse address');

        $this->assertDatabaseHas('users', [
            'email' => 'updated@example.com',
            'first_name' => 'Updated',
            'last_name' => 'User',
            'city' => 'Lviv',
            'address_line' => 'Warehouse address',
        ]);
    }

    public function testProfilePayloadValidation(): void
    {
        $this->actingAs($this->user())
            ->patchJson('/api/v1/profile', [
                'first_name' => str_repeat('a', 121),
                'last_name' => str_repeat('a', 121),
                'email' => 'not-an-email',
                'avatar_path' => str_repeat('a', 256),
                'phone' => str_repeat('1', 41),
                'country' => str_repeat('a', 121),
                'city' => str_repeat('a', 121),
                'postal_code' => str_repeat('1', 33),
                'address_line' => str_repeat('a', 256),
            ])
            ->assertUnprocessable()
            ->assertJsonValidationErrors([
                'first_name',
                'last_name',
                'email',
                'avatar_path',
                'phone',
                'country',
                'city',
                'postal_code',
                'address_line',
            ]);
    }

    public function testPaymentMethodsCanBeListedAndDefaultIsUnique(): void
    {
        $user = $this->user();
        $existingDefault = PaymentMethod::query()
            ->where('user_id', $user->id)
            ->where('is_default', true)
            ->firstOrFail();

        $this->actingAs($user)
            ->getJson('/api/v1/profile/payment-methods')
            ->assertOk()
            ->assertJsonCount(2, 'data')
            ->assertJsonPath('data.0.last_four', '4242')
            ->assertJsonPath('data.0.is_default', true);

        $response = $this->actingAs($user)
            ->postJson('/api/v1/profile/payment-methods', [
                'mock_token' => 'pm_mock_mastercard_2222',
                'is_default' => true,
            ])
            ->assertCreated()
            ->assertJsonPath('data.last_four', '2222')
            ->assertJsonPath('data.is_default', true);

        $this->assertDatabaseHas('payment_methods', [
            'id' => $existingDefault->id,
            'is_default' => false,
        ]);

        $this->actingAs($user)
            ->getJson('/api/v1/profile/payment-methods')
            ->assertOk()
            ->assertJsonPath('data.0.id', $response->json('data.id'))
            ->assertJsonPath('data.0.is_default', true);
    }

    public function testPaymentMethodsRejectRawCardsAndSupportMockTokens(): void
    {
        $this->actingAs($this->user())
            ->postJson('/api/v1/profile/payment-methods', [
                'mock_token' => 'pm_mock_visa_1111',
                'card_number' => '4111111111111111',
            ])
            ->assertUnprocessable()
            ->assertJsonValidationErrors(['card_number']);

        $this->actingAs($this->user())
            ->postJson('/api/v1/profile/payment-methods', [
                'mock_token' => 'invalid-token',
            ])
            ->assertUnprocessable()
            ->assertJsonValidationErrors(['mock_token']);

        $this->actingAs($this->user())
            ->postJson('/api/v1/profile/payment-methods', [
                'mock_token' => 'pm_mock_visa_4242',
            ])
            ->assertUnprocessable()
            ->assertJsonValidationErrors(['mock_token']);

        $response = $this->actingAs($this->user())
            ->postJson('/api/v1/profile/payment-methods', [
                'mock_token' => 'pm_mock_visa_1111',
                'is_default' => true,
            ])
            ->assertCreated()
            ->assertJsonPath('data.last_four', '1111');

        $this->actingAs($this->user())
            ->deleteJson('/api/v1/profile/payment-methods/' . $response->json('data.id'))
            ->assertNoContent();
    }

    public function testWishlistCanBeListedAddedIdempotentlyAndRemoved(): void
    {
        $product = Product::query()->where('slug', 'soft-wool-beanie')->firstOrFail();

        $first = $this->actingAs($this->user())
            ->postJson('/api/v1/profile/wishlist', ['product_id' => $product->id])
            ->assertCreated()
            ->assertJsonPath('data.product.slug', 'soft-wool-beanie');

        $this->actingAs($this->user())
            ->postJson('/api/v1/profile/wishlist', ['product_id' => $product->id])
            ->assertCreated()
            ->assertJsonPath('data.id', $first->json('data.id'));

        $this->actingAs($this->user())
            ->getJson('/api/v1/profile/wishlist')
            ->assertOk()
            ->assertJsonFragment(['slug' => 'soft-wool-beanie']);

        $this->actingAs($this->user())
            ->deleteJson('/api/v1/profile/wishlist/' . $first->json('data.id'))
            ->assertNoContent();
    }

    public function testInactiveProductCannotBeWishlisted(): void
    {
        $product = Product::query()->where('slug', 'hidden-archive-jacket')->firstOrFail();

        $this->actingAs($this->user())
            ->postJson('/api/v1/profile/wishlist', ['product_id' => $product->id])
            ->assertUnprocessable()
            ->assertJsonValidationErrors(['product_id']);
    }

    public function testOtherUsersSavedObjectsAreNotMutable(): void
    {
        $other = User::factory()->create();
        $paymentMethod = PaymentMethod::factory()->for($other)->create();
        $wishlistItem = WishlistItem::factory()->for($other)->create();

        $this->actingAs($this->user())
            ->deleteJson('/api/v1/profile/payment-methods/' . $paymentMethod->id)
            ->assertNotFound();

        $this->actingAs($this->user())
            ->deleteJson('/api/v1/profile/wishlist/' . $wishlistItem->id)
            ->assertNotFound();
    }

    private function user(): User
    {
        return User::query()->where('email', 'user@example.com')->firstOrFail();
    }
}
