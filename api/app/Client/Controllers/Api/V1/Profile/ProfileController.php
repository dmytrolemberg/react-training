<?php

declare(strict_types = 1);

namespace App\Client\Controllers\Api\V1\Profile;

use App\Models\User\User;
use Illuminate\Http\Request;
use App\Models\Account\Address;
use App\Models\Commerce\CartItem;
use App\Models\Commerce\CartStatus;
use App\Http\Controllers\Controller;
use App\Models\Account\PaymentMethod;
use Illuminate\Database\Eloquent\Builder;
use App\Client\UseCases\Profile\UpdateProfileUseCase;
use App\Client\Resources\Api\V1\Profile\ProfileResource;
use App\Client\Requests\Api\V1\Profile\UpdateProfileRequest;

class ProfileController extends Controller
{
    public function show(Request $request): ProfileResource
    {
        $user = $this->profileUser($request);

        return new ProfileResource($user);
    }

    public function update(UpdateProfileRequest $request, UpdateProfileUseCase $useCase): ProfileResource
    {
        $user = $useCase->execute($this->user($request), $request->payload());

        return new ProfileResource($user);
    }

    private function profileUser(Request $request): User
    {
        $user = $request->user();
        abort_unless($user instanceof User, 401);

        $user->setRelation('addresses', Address::query()
            ->where('user_id', $user->id)
            ->orderByDesc('is_default')
            ->orderBy('id')
            ->get());
        $user->setRelation('paymentMethods', PaymentMethod::query()
            ->where('user_id', $user->id)
            ->orderByDesc('is_default')
            ->orderBy('id')
            ->get());
        $user->loadCount(['orders', 'reviews', 'wishlistItems']);

        $cartItemsCount = (int) CartItem::query()
            ->whereHas('cart', fn(Builder $query): Builder => $query
                ->where('user_id', $user->id)
                ->where('status', CartStatus::Active->value))
            ->sum('quantity');

        $user->setAttribute('cart_items_count', $cartItemsCount);

        return $user;
    }

    private function user(Request $request): User
    {
        $user = $request->user();
        abort_unless($user instanceof User, 401);

        return $user;
    }
}
