<?php

declare(strict_types = 1);

namespace App\Client\Controllers\Api\V1\Checkout;

use App\Models\User\User;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use App\Http\Controllers\Controller;
use App\Models\Commerce\DeliveryMethod;
use App\Client\Services\Cart\CartResolver;
use App\Client\Services\Cart\CartPricingService;
use App\Client\UseCases\Checkout\CheckoutCartUseCase;
use App\Client\Resources\Api\V1\Order\OrderDetailResource;
use App\Client\Requests\Api\V1\Checkout\CheckoutOrderRequest;
use App\Client\Resources\Api\V1\Profile\PaymentMethodResource;

class CheckoutController extends Controller
{
    public function options(Request $request, CartResolver $cartResolver, CartPricingService $pricingService): JsonResponse
    {
        $user = $this->user($request);
        $cart = $cartResolver->activeForUser($user)->load('items');
        $configuredCurrency = config('app.currency', 'EUR');
        $currency = is_string($configuredCurrency) ? $configuredCurrency : 'EUR';
        $paymentMethods = $user->paymentMethods()->orderByDesc('is_default')->orderBy('id')->get();

        $deliveryMethods = collect(DeliveryMethod::cases())->map(fn(DeliveryMethod $method): array => [
            'value' => $method->value,
            'label' => $method->label(),
            'price' => [
                'cents' => $pricingService->deliveryCents($method, $pricingService->summarize($cart)['subtotal_cents']),
                'currency' => $currency,
            ],
        ])->values();

        return response()->json([
            'data' => [
                'delivery_methods' => $deliveryMethods,
                'payment_methods' => PaymentMethodResource::collection($paymentMethods),
            ],
        ]);
    }

    public function store(CheckoutOrderRequest $request, CheckoutCartUseCase $useCase): JsonResponse
    {
        $order = $useCase->execute($this->user($request), $request->validated());

        return new OrderDetailResource($order)->response()->setStatusCode(201);
    }

    private function user(Request $request): User
    {
        $user = $request->user();
        abort_unless($user instanceof User, 401);

        return $user;
    }
}
