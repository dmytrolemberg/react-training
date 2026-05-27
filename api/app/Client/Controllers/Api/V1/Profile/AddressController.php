<?php

declare(strict_types = 1);

namespace App\Client\Controllers\Api\V1\Profile;

use App\Models\User\User;
use Illuminate\Http\Request;
use App\Models\Account\Address;
use Illuminate\Http\JsonResponse;
use App\Http\Controllers\Controller;
use App\Client\UseCases\Profile\StoreAddressUseCase;
use App\Client\UseCases\Profile\DeleteAddressUseCase;
use App\Client\UseCases\Profile\UpdateAddressUseCase;
use App\Client\Resources\Api\V1\Profile\AddressResource;
use App\Client\Requests\Api\V1\Profile\StoreAddressRequest;
use App\Client\Requests\Api\V1\Profile\UpdateAddressRequest;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;

class AddressController extends Controller
{
    public function index(Request $request): AnonymousResourceCollection
    {
        $addresses = Address::query()
            ->where('user_id', $this->user($request)->id)
            ->orderByDesc('is_default')
            ->orderBy('id')
            ->get();

        return AddressResource::collection($addresses);
    }

    public function store(StoreAddressRequest $request, StoreAddressUseCase $useCase): JsonResponse
    {
        $address = $useCase->execute($this->user($request), $request->validated());

        return new AddressResource($address)->response()->setStatusCode(201);
    }

    public function update(UpdateAddressRequest $request, int $address, UpdateAddressUseCase $useCase): AddressResource
    {
        $model = Address::query()->findOrFail($address);

        return new AddressResource($useCase->execute($this->user($request), $model, $request->validated()));
    }

    public function destroy(Request $request, int $address, DeleteAddressUseCase $useCase): JsonResponse
    {
        $model = Address::query()->findOrFail($address);
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
