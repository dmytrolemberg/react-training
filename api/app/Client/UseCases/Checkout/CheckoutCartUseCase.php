<?php

declare(strict_types = 1);

namespace App\Client\UseCases\Checkout;

use App\Models\User\User;
use App\Models\Commerce\Order;
use App\Models\Catalog\Product;
use Illuminate\Support\Facades\DB;
use App\Models\Commerce\OrderStatus;
use App\Models\Account\PaymentMethod;
use App\Models\Commerce\DeliveryMethod;
use App\Client\Services\Cart\CartResolver;
use Illuminate\Validation\ValidationException;
use App\Client\Services\Cart\CartPricingService;
use App\Client\Services\Checkout\FakePaymentService;
use App\Client\Services\Checkout\OrderNumberGenerator;
use App\Client\Services\Product\InventoryAvailabilityService;

class CheckoutCartUseCase
{
    public function __construct(
        private readonly CartResolver $cartResolver,
        private readonly CartPricingService $cartPricingService,
        private readonly InventoryAvailabilityService $inventoryAvailability,
        private readonly FakePaymentService $paymentService,
        private readonly OrderNumberGenerator $orderNumberGenerator,
    ) {}

    /**
     * @param array<string, mixed> $data
     * @throws ValidationException
     */
    public function execute(User $user, array $data): Order
    {
        return DB::transaction(function () use ($user, $data): Order {
            $cart = $this->cartResolver->activeForUser($user)
                ->load(['items.product.brand', 'items.product.category', 'items.product.attributes']);

            if ($cart->items->isEmpty()) {
                throw ValidationException::withMessages(['cart' => ['Your cart is empty.']]);
            }

            $lockedProducts = Product::query()
                ->whereKey($cart->items->pluck('product_id')->all())
                ->lockForUpdate()
                ->with(['brand', 'category', 'attributes'])
                ->get()
                ->keyBy('id');

            foreach ($cart->items as $item) {
                $product = $lockedProducts->get($item->product_id);
                if (!$product instanceof Product) {
                    throw ValidationException::withMessages(['cart' => ['One of the cart products is no longer available.']]);
                }

                $this->inventoryAvailability->ensureProductCanBePurchased($product, $item->quantity, 'cart');

                $item->forceFill([
                    'unit_price_cents' => $product->price_cents,
                ])->save();
                $item->setRelation('product', $product);
            }

            $cart->refresh()->load(['items.product.brand', 'items.product.category', 'items.product.attributes']);

            /** @var array{first_name: string, last_name: string, country?: string, city: string, postal_code: string, address_line: string} $shipping */
            $shipping = $data['shipping_address'];
            /** @var array{email: string, phone?: string|null} $contact */
            $contact = $data['contact'];
            $deliveryMethodValue = $data['delivery_method'] ?? DeliveryMethod::Standard->value;
            $paymentMethodType = $data['payment_method_type'] ?? 'mock_card';
            $deliveryMethod = DeliveryMethod::from(is_string($deliveryMethodValue) ? $deliveryMethodValue : DeliveryMethod::Standard->value);
            $paymentMethodType = is_string($paymentMethodType) ? $paymentMethodType : 'mock_card';
            $paymentMethod = $this->resolvePaymentMethod($user, $paymentMethodType, $data['payment_method_id'] ?? null);
            $payment = $this->paymentService->charge($paymentMethodType, $paymentMethod);
            $totals = $this->cartPricingService->summarize($cart, $deliveryMethod);

            $order = Order::query()->create([
                'user_id' => $user->id,
                'number' => $this->orderNumberGenerator->next(),
                'status' => OrderStatus::Processing->value,
                'payment_status' => $payment['status']->value,
                'subtotal_cents' => $totals['subtotal_cents'],
                'delivery_cents' => $totals['delivery_cents'],
                'tax_cents' => $totals['tax_cents'],
                'total_cents' => $totals['total_cents'],
                'contact_email' => $contact['email'],
                'contact_phone' => $contact['phone'] ?? null,
                'shipping_first_name' => $shipping['first_name'],
                'shipping_last_name' => $shipping['last_name'],
                'shipping_country' => $shipping['country'] ?? 'Ukraine',
                'shipping_city' => $shipping['city'],
                'shipping_postal_code' => $shipping['postal_code'],
                'shipping_address_line' => $shipping['address_line'],
                'delivery_method' => $deliveryMethod->value,
                'payment_method_type' => $payment['type'],
                'payment_method_label' => $payment['label'],
                'transaction_id' => $payment['transaction_id'],
                'placed_at' => now(),
            ]);

            foreach ($cart->items as $item) {
                $product = $item->product;

                $order->items()->create([
                    'product_id' => $product->id,
                    'product_slug' => $product->slug,
                    'sku' => $product->sku,
                    'name' => $product->name,
                    'brand_name' => $product->brand->name,
                    'category_name' => $product->category->name,
                    'unit_price_cents' => $product->price_cents,
                    'quantity' => $item->quantity,
                    'total_cents' => $product->price_cents * $item->quantity,
                    'attributes' => $product->attributes->take(5)->map(fn($attribute): array => [
                        'name' => $attribute->name,
                        'value' => $attribute->value,
                    ])->values()->all(),
                ]);

                $product->decrement('stock_quantity', $item->quantity);
            }

            foreach ([
                ['label' => 'Order placed', 'description' => 'Confirmation sent to ' . $contact['email'], 'position' => 10],
                ['label' => $payment['status']->value === 'paid' ? 'Payment confirmed' : 'Payment pending', 'description' => $payment['label'], 'position' => 20],
                ['label' => 'Preparing shipment', 'description' => 'Waiting for fulfillment', 'position' => 30],
            ] as $event) {
                $order->statusEvents()->create([
                    'status' => OrderStatus::Processing->value,
                    'label' => $event['label'],
                    'description' => $event['description'],
                    'occurred_at' => now(),
                    'position' => $event['position'],
                ]);
            }

            $cart->items()->delete();

            return $order->refresh()->load(['items', 'statusEvents']);
        });
    }

    /**
     * @throws ValidationException
     */
    private function resolvePaymentMethod(User $user, string $paymentMethodType, mixed $paymentMethodId): ?PaymentMethod
    {
        if ($paymentMethodType === 'cash_on_delivery') {
            return null;
        }

        $query = PaymentMethod::query()
            ->where('user_id', $user->id)
            ->where('type', 'mock_card');

        if ($paymentMethodId !== null) {
            $query->whereKey(is_numeric($paymentMethodId) ? (int) $paymentMethodId : 0);
        } else {
            $query->where('is_default', true);
        }

        $paymentMethod = $query->first();

        if (!$paymentMethod instanceof PaymentMethod) {
            throw ValidationException::withMessages(['payment_method_id' => ['Select a valid mock payment method.']]);
        }

        return $paymentMethod;
    }
}
