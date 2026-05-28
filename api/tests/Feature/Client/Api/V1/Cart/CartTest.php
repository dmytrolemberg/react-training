<?php

declare(strict_types = 1);

namespace Tests\Feature\Client\Api\V1\Cart;

use Tests\TestCase;
use App\Models\User\User;
use App\Models\Catalog\Product;
use App\Models\Commerce\CartItem;

class CartTest extends TestCase
{
    public function testCartRequiresAuthentication(): void
    {
        $this->getJson('/api/v1/cart')->assertUnauthorized();
    }

    public function testAuthenticatedUserCanReadSeededCart(): void
    {
        $this->actingAs($this->user())
            ->getJson('/api/v1/cart')
            ->assertOk()
            ->assertJsonPath('data.item_count', 2)
            ->assertJsonPath('data.summary.subtotal.cents', 20100)
            ->assertJsonPath('data.summary.subtotal.currency', 'EUR')
            ->assertJsonPath('data.currency', 'EUR')
            ->assertJsonMissingPath('data.status')
            ->assertJsonMissingPath('data.items.0.product.brand')
            ->assertJsonMissingPath('data.items.0.product.category');
    }

    public function testAddingExistingProductMergesQuantity(): void
    {
        $product = Product::query()->where('slug', 'everyday-carry-pack')->firstOrFail();

        $this->actingAs($this->user())
            ->postJson('/api/v1/cart/items', [
                'product_id' => $product->id,
                'quantity' => 2,
            ])
            ->assertOk()
            ->assertJsonPath('data.item_count', 4);

        $this->assertDatabaseHas('cart_items', [
            'product_id' => $product->id,
            'quantity' => 3,
        ]);
    }

    public function testNewProductCanBeAddedToCart(): void
    {
        $product = Product::query()->where('slug', 'soft-wool-beanie')->firstOrFail();

        $this->actingAs($this->user())
            ->postJson('/api/v1/cart/items', [
                'product_id' => $product->id,
                'quantity' => 2,
            ])
            ->assertOk()
            ->assertJsonPath('data.item_count', 4);

        $this->assertDatabaseHas('cart_items', [
            'product_id' => $product->id,
            'quantity' => 2,
        ]);
    }

    public function testCartItemPayloadValidation(): void
    {
        $item = $this->cartItem();

        $this->actingAs($this->user())
            ->postJson('/api/v1/cart/items', [
                'product_id' => 999999,
                'quantity' => 0,
            ])
            ->assertUnprocessable()
            ->assertJsonValidationErrors(['product_id', 'quantity']);

        $this->actingAs($this->user())
            ->patchJson('/api/v1/cart/items/' . $item->id, [
                'quantity' => 51,
            ])
            ->assertUnprocessable()
            ->assertJsonValidationErrors(['quantity']);
    }

    public function testOutOfStockProductCannotBeAdded(): void
    {
        $product = Product::query()->where('slug', 'ceramic-cable-dock')->firstOrFail();

        $this->actingAs($this->user())
            ->postJson('/api/v1/cart/items', [
                'product_id' => $product->id,
                'quantity' => 1,
            ])
            ->assertUnprocessable()
            ->assertJsonValidationErrors(['quantity']);
    }

    public function testInactiveProductCannotBeAdded(): void
    {
        $product = Product::query()->where('slug', 'hidden-archive-jacket')->firstOrFail();

        $this->actingAs($this->user())
            ->postJson('/api/v1/cart/items', [
                'product_id' => $product->id,
                'quantity' => 1,
            ])
            ->assertUnprocessable()
            ->assertJsonValidationErrors(['product_id']);
    }

    public function testCartItemUpdateRejectsQuantityAboveCurrentStock(): void
    {
        $item = $this->cartItem();
        $item->product()->update(['stock_quantity' => 0]);

        $this->actingAs($this->user())
            ->patchJson('/api/v1/cart/items/' . $item->id, ['quantity' => 1])
            ->assertUnprocessable()
            ->assertJsonValidationErrors(['quantity']);
    }

    public function testCartItemQuantityCanBeUpdated(): void
    {
        $item = $this->cartItem();

        $this->actingAs($this->user())
            ->patchJson('/api/v1/cart/items/' . $item->id, ['quantity' => 2])
            ->assertOk()
            ->assertJsonPath('data.item_count', 3);

        $this->assertDatabaseHas('cart_items', [
            'id' => $item->id,
            'quantity' => 2,
        ]);
    }

    public function testCartItemCanBeRemoved(): void
    {
        $item = $this->cartItem();

        $this->actingAs($this->user())
            ->deleteJson('/api/v1/cart/items/' . $item->id)
            ->assertOk()
            ->assertJsonPath('data.item_count', 1);

        $this->assertDatabaseMissing('cart_items', ['id' => $item->id]);
    }

    public function testCartCanBeCleared(): void
    {
        $this->actingAs($this->user())
            ->deleteJson('/api/v1/cart')
            ->assertOk()
            ->assertJsonPath('data.item_count', 0);
    }

    public function testUserCannotMutateAnotherUsersCartItem(): void
    {
        $other = User::factory()->create();
        $item = $this->cartItem();

        $this->actingAs($other)
            ->patchJson('/api/v1/cart/items/' . $item->id, ['quantity' => 2])
            ->assertNotFound();

        $this->actingAs($other)
            ->deleteJson('/api/v1/cart/items/' . $item->id)
            ->assertNotFound();
    }

    private function user(): User
    {
        return User::query()->where('email', 'user@example.com')->firstOrFail();
    }

    private function cartItem(): CartItem
    {
        return CartItem::query()
            ->whereHas('cart', fn($query) => $query->where('user_id', $this->user()->id))
            ->firstOrFail();
    }
}
