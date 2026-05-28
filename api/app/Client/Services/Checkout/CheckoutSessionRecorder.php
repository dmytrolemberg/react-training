<?php

declare(strict_types = 1);

namespace App\Client\Services\Checkout;

use App\Models\User\User;
use App\Models\Commerce\Cart;
use App\Models\Commerce\Order;
use App\Models\Commerce\DeliveryMethod;
use App\Models\Commerce\CheckoutSession;
use App\Client\Services\Cart\CartResolver;
use App\Models\Commerce\CheckoutSessionStatus;
use App\Client\Services\Cart\CartPricingService;

class CheckoutSessionRecorder
{
    public function __construct(
        private readonly CartResolver $cartResolver,
        private readonly CartPricingService $pricingService,
    ) {}

    /**
     * @param array<string, mixed> $payload
     */
    public function completed(User $user, array $payload, Order $order): CheckoutSession
    {
        $cart = Cart::query()->where('user_id', $user->id)->first();

        return CheckoutSession::query()->create([
            'user_id' => $user->id,
            'cart_id' => $cart?->id,
            'order_id' => $order->id,
            'status' => CheckoutSessionStatus::Completed->value,
            'contact_email' => $order->contact_email,
            'delivery_method' => $order->delivery_method->value,
            'payment_method_type' => $order->payment_method_type,
            'subtotal_cents' => $order->subtotal_cents,
            'delivery_cents' => $order->delivery_cents,
            'tax_cents' => $order->tax_cents,
            'total_cents' => $order->total_cents,
            'item_count' => $order->items()->sum('quantity'),
            'payload' => $this->safePayload($payload),
            'completed_at' => now(),
        ]);
    }

    /**
     * @param array<string, mixed> $payload
     * @param array<string, mixed> $errors
     */
    public function failed(User $user, array $payload, array $errors, string $stage = 'checkout'): CheckoutSession
    {
        $cart = $this->cartResolver->activeForUser($user)->load('items');
        $delivery = $this->deliveryMethod($payload);
        $summary = $this->summary($cart, $delivery);

        return CheckoutSession::query()->create([
            'user_id' => $user->id,
            'cart_id' => $cart->id,
            'order_id' => null,
            'status' => CheckoutSessionStatus::Failed->value,
            'failure_stage' => $stage,
            'failure_reason' => $this->failureReason($errors),
            'contact_email' => $this->contactEmail($payload),
            'delivery_method' => $delivery?->value,
            'payment_method_type' => is_string($payload['payment_method_type'] ?? null) ? $payload['payment_method_type'] : null,
            'subtotal_cents' => $summary['subtotal_cents'],
            'delivery_cents' => $summary['delivery_cents'],
            'tax_cents' => $summary['tax_cents'],
            'total_cents' => $summary['total_cents'],
            'item_count' => $summary['item_count'],
            'payload' => $this->safePayload($payload),
            'failed_at' => now(),
        ]);
    }

    /**
     * @return array{subtotal_cents: int, delivery_cents: int, tax_cents: int, total_cents: int, item_count: int}
     */
    private function summary(Cart $cart, ?DeliveryMethod $delivery): array
    {
        try {
            return $this->pricingService->summarize($cart, $delivery);
        } catch (\Throwable) {
            return [
                'subtotal_cents' => 0,
                'delivery_cents' => 0,
                'tax_cents' => 0,
                'total_cents' => 0,
                'item_count' => 0,
            ];
        }
    }

    /**
     * @param array<string, mixed> $payload
     */
    private function deliveryMethod(array $payload): ?DeliveryMethod
    {
        $delivery = $payload['delivery_method'] ?? null;

        return is_string($delivery) ? DeliveryMethod::tryFrom($delivery) : null;
    }

    /**
     * @param array<string, mixed> $payload
     */
    private function contactEmail(array $payload): ?string
    {
        $contact = $payload['contact'] ?? null;

        return is_array($contact) && is_string($contact['email'] ?? null) ? $contact['email'] : null;
    }

    /**
     * @param array<string, mixed> $payload
     * @return array<string, mixed>
     */
    private function safePayload(array $payload): array
    {
        unset($payload['card_number'], $payload['card_cvc'], $payload['card_expiry']);

        return $payload;
    }

    /**
     * @param array<string, mixed> $errors
     */
    private function failureReason(array $errors): string
    {
        $messages = [];

        foreach ($errors as $field => $fieldErrors) {
            $fieldMessages = is_array($fieldErrors) ? $fieldErrors : [$fieldErrors];
            foreach ($fieldMessages as $message) {
                if (is_string($message)) {
                    $messages[] = $field . ': ' . $message;
                }
            }
        }

        return implode(' ', $messages);
    }
}
