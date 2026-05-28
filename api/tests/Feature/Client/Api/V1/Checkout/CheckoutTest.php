<?php

declare(strict_types = 1);

namespace Tests\Feature\Client\Api\V1\Checkout;

use Tests\TestCase;
use App\Models\User\User;
use App\Models\Commerce\Cart;
use App\Models\Catalog\Product;
use App\Models\Account\PaymentMethod;

class CheckoutTest extends TestCase
{
    public function testCheckoutRequiresAuthentication(): void
    {
        $this->postJson('/api/v1/checkout/orders', $this->payload())
            ->assertUnauthorized();
    }

    public function testCheckoutOptionsIncludeDeliveryAndPaymentMethods(): void
    {
        $this->actingAs($this->user())
            ->getJson('/api/v1/checkout/options')
            ->assertOk()
            ->assertJsonPath('data.delivery_methods.0.value', 'standard')
            ->assertJsonPath('data.delivery_methods.0.price.currency', 'EUR')
            ->assertJsonPath('data.payment_methods.0.last_four', '4242');
    }

    public function testCheckoutRejectsEmptyCart(): void
    {
        $cart = Cart::query()
            ->where('user_id', $this->user()->id)
            ->firstOrFail();
        $cart->items()->delete();

        $this->actingAs($this->user())
            ->postJson('/api/v1/checkout/orders', $this->payload())
            ->assertUnprocessable()
            ->assertJsonValidationErrors(['cart']);
    }

    public function testCheckoutRejectsRawCardData(): void
    {
        $payload = $this->payload() + ['card_number' => '4111111111111111'];

        $this->actingAs($this->user())
            ->postJson('/api/v1/checkout/orders', $payload)
            ->assertUnprocessable()
            ->assertJsonValidationErrors(['card_number']);
    }

    public function testCheckoutRejectsInvalidContactAddressDeliveryAndPaymentType(): void
    {
        $payload = array_replace_recursive($this->payload(), [
            'contact' => ['email' => 'not-an-email'],
            'shipping_address' => ['city' => ''],
            'delivery_method' => 'same-day-drone',
            'payment_method_type' => 'crypto',
        ]);

        $this->actingAs($this->user())
            ->postJson('/api/v1/checkout/orders', $payload)
            ->assertUnprocessable()
            ->assertJsonValidationErrors([
                'contact.email',
                'shipping_address.city',
                'delivery_method',
                'payment_method_type',
            ]);
    }

    public function testCheckoutRejectsInvalidPaymentMethod(): void
    {
        $payload = $this->payload();
        $payload['payment_method_id'] = 999999;

        $this->actingAs($this->user())
            ->postJson('/api/v1/checkout/orders', $payload)
            ->assertUnprocessable()
            ->assertJsonValidationErrors(['payment_method_id']);
    }

    public function testCheckoutRejectsPaymentMethodOwnedByAnotherUser(): void
    {
        $other = User::factory()->create();
        $paymentMethod = PaymentMethod::factory()->for($other)->create();
        $payload = $this->payload();
        $payload['payment_method_id'] = $paymentMethod->id;

        $this->actingAs($this->user())
            ->postJson('/api/v1/checkout/orders', $payload)
            ->assertUnprocessable()
            ->assertJsonValidationErrors(['payment_method_id']);
    }

    public function testCheckoutRejectsCartQuantityThatExceedsStock(): void
    {
        $product = Product::query()->where('slug', 'everyday-carry-pack')->firstOrFail();
        $product->forceFill(['stock_quantity' => 0])->save();

        $this->actingAs($this->user())
            ->postJson('/api/v1/checkout/orders', $this->payload())
            ->assertUnprocessable()
            ->assertJsonValidationErrors(['quantity']);
    }

    public function testCheckoutCreatesOrderClearsCartAndDecrementsStock(): void
    {
        $product = Product::query()->where('slug', 'everyday-carry-pack')->firstOrFail();
        $beforeStock = $product->stock_quantity;

        $this->actingAs($this->user())
            ->postJson('/api/v1/checkout/orders', $this->payload())
            ->assertCreated()
            ->assertJsonPath('data.status', 'processing')
            ->assertJsonPath('data.summary.subtotal.cents', 20100)
            ->assertJsonPath('data.summary.subtotal.currency', 'EUR')
            ->assertJsonPath('data.payment.method_label', 'Visa ending 4242');

        $this->assertDatabaseHas('orders', [
            'user_id' => $this->user()->id,
            'subtotal_cents' => 20100,
            'payment_status' => 'paid',
        ]);
        $cart = Cart::query()->where('user_id', $this->user()->id)->firstOrFail();
        $this->assertSame(0, $cart->items()->count());
        $this->assertSame($beforeStock - 1, $product->refresh()->stock_quantity);
    }

    public function testCashOnDeliveryCheckoutDoesNotRequireSavedPaymentMethod(): void
    {
        $user = $this->user();
        PaymentMethod::query()->where('user_id', $user->id)->delete();

        $payload = $this->payload();
        $payload['payment_method_type'] = 'cash_on_delivery';

        $this->actingAs($user)
            ->postJson('/api/v1/checkout/orders', $payload)
            ->assertCreated()
            ->assertJsonPath('data.payment_status', 'pending')
            ->assertJsonPath('data.payment.method_type', 'cash_on_delivery')
            ->assertJsonPath('data.payment.method_label', 'Pay on delivery');
    }

    /**
     * @return array<string, mixed>
     */
    private function payload(): array
    {
        return [
            'contact' => [
                'email' => 'dmytro@example.com',
                'phone' => '+380000000000',
            ],
            'shipping_address' => [
                'first_name' => 'Dmytro',
                'last_name' => 'Orikhovskyi',
                'country' => 'Ukraine',
                'city' => 'Kyiv',
                'postal_code' => '01001',
                'address_line' => 'Street address placeholder',
            ],
            'delivery_method' => 'standard',
            'payment_method_type' => 'mock_card',
        ];
    }

    private function user(): User
    {
        return User::query()->where('email', 'user@example.com')->firstOrFail();
    }
}
