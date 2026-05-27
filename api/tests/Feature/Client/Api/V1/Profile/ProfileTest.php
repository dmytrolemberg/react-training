<?php

declare(strict_types = 1);

namespace Tests\Feature\Client\Api\V1\Profile;

use Tests\TestCase;
use App\Models\User\User;
use App\Models\Account\Address;
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
            ->assertJsonPath('data.stats.orders_count', 1)
            ->assertJsonPath('data.addresses.0.is_default', true)
            ->assertJsonPath('data.payment_methods.0.last_four', '4242');
    }

    public function testAddressesCanBeListed(): void
    {
        $this->actingAs($this->user())
            ->getJson('/api/v1/profile/addresses')
            ->assertOk()
            ->assertJsonCount(2, 'data')
            ->assertJsonPath('data.0.label', 'Home')
            ->assertJsonPath('data.0.is_default', true);
    }

    public function testProfileCanBeUpdatedAndValidatesUniqueEmail(): void
    {
        $other = User::factory()->create(['email' => 'taken@example.com']);

        $this->actingAs($this->user())
            ->patchJson('/api/v1/profile', [
                'name' => 'Updated User',
                'email' => $other->email,
            ])
            ->assertUnprocessable()
            ->assertJsonValidationErrors(['email']);

        $this->actingAs($this->user())
            ->patchJson('/api/v1/profile', [
                'name' => 'Updated User',
                'email' => 'updated@example.com',
            ])
            ->assertOk()
            ->assertJsonPath('data.name', 'Updated User')
            ->assertJsonPath('data.email', 'updated@example.com');
    }

    public function testAddressPayloadValidation(): void
    {
        $address = Address::query()->where('user_id', $this->user()->id)->firstOrFail();

        $this->actingAs($this->user())
            ->postJson('/api/v1/profile/addresses', [
                'label' => str_repeat('a', 81),
            ])
            ->assertUnprocessable()
            ->assertJsonValidationErrors(['label', 'first_name', 'last_name', 'city', 'postal_code', 'address_line']);

        $this->actingAs($this->user())
            ->patchJson('/api/v1/profile/addresses/' . $address->id, [
                'is_default' => 'not-a-boolean',
            ])
            ->assertUnprocessable()
            ->assertJsonValidationErrors(['is_default']);
    }

    public function testAddressesCanBeCreatedUpdatedAndDeletedWithSingleDefault(): void
    {
        $user = $this->user();
        $existingDefault = Address::query()->where('user_id', $user->id)->where('is_default', true)->firstOrFail();

        $response = $this->actingAs($user)
            ->postJson('/api/v1/profile/addresses', [
                'label' => 'Warehouse',
                'first_name' => 'Dmytro',
                'last_name' => 'Orikhovskyi',
                'city' => 'Lviv',
                'postal_code' => '79000',
                'address_line' => 'Warehouse address',
                'is_default' => true,
            ])
            ->assertCreated()
            ->assertJsonPath('data.is_default', true);

        $this->assertDatabaseHas('addresses', [
            'id' => $existingDefault->id,
            'is_default' => false,
        ]);

        $addressId = (int) $response->json('data.id');
        $this->actingAs($user)
            ->patchJson('/api/v1/profile/addresses/' . $addressId, ['label' => 'Main warehouse'])
            ->assertOk()
            ->assertJsonPath('data.label', 'Main warehouse');

        $this->actingAs($user)
            ->deleteJson('/api/v1/profile/addresses/' . $addressId)
            ->assertNoContent();
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
        $address = Address::factory()->for($other)->create();
        $paymentMethod = PaymentMethod::factory()->for($other)->create();
        $wishlistItem = WishlistItem::factory()->for($other)->create();

        $this->actingAs($this->user())
            ->patchJson('/api/v1/profile/addresses/' . $address->id, ['label' => 'Nope'])
            ->assertNotFound();

        $this->actingAs($this->user())
            ->deleteJson('/api/v1/profile/addresses/' . $address->id)
            ->assertNotFound();

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
