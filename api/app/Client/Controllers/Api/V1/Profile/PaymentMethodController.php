<?php

declare(strict_types = 1);

namespace App\Client\Controllers\Api\V1\Profile;

use App\Models\User\User;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use App\Http\Controllers\Controller;
use App\Models\Account\PaymentMethod;
use App\Client\UseCases\Profile\StorePaymentMethodUseCase;
use App\Client\UseCases\Profile\DeletePaymentMethodUseCase;
use App\Client\Resources\Api\V1\Profile\PaymentMethodResource;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;
use App\Client\Requests\Api\V1\Profile\StorePaymentMethodRequest;

class PaymentMethodController extends Controller
{
    public function index(Request $request): AnonymousResourceCollection
    {
        $paymentMethods = PaymentMethod::query()
            ->where('user_id', $this->user($request)->id)
            ->orderByDesc('is_default')
            ->orderBy('id')
            ->get();

        return PaymentMethodResource::collection($paymentMethods);
    }

    public function store(StorePaymentMethodRequest $request, StorePaymentMethodUseCase $useCase): JsonResponse
    {
        $paymentMethod = $useCase->execute($this->user($request), $request->payload());

        return new PaymentMethodResource($paymentMethod)->response()->setStatusCode(201);
    }

    public function destroy(Request $request, int $paymentMethod, DeletePaymentMethodUseCase $useCase): JsonResponse
    {
        $model = PaymentMethod::query()->findOrFail($paymentMethod);
        $useCase->execute($this->user($request), $model);

        return response()->json(status: 204);
    }

    private function user(Request $request): User
    {
        $user = $request->user();
        abort_unless($user instanceof User, 401);

        return $user;
    }
}
