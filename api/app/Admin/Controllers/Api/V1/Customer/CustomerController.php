<?php

declare(strict_types = 1);

namespace App\Admin\Controllers\Api\V1\Customer;

use App\Models\User\User;
use App\Models\User\UserRole;
use Illuminate\Http\Response;
use App\Http\Controllers\Controller;
use Illuminate\Database\Eloquent\Builder;
use App\Admin\Resources\Api\V1\Customer\CustomerResource;
use App\Admin\Requests\Api\V1\Customer\CustomerIndexRequest;
use App\Admin\Requests\Api\V1\Customer\StoreCustomerRequest;
use App\Admin\Requests\Api\V1\Customer\UpdateCustomerRequest;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;

class CustomerController extends Controller
{
    public function index(CustomerIndexRequest $request): AnonymousResourceCollection
    {
        $query = User::query()
            ->where('role', UserRole::User->value)
            ->withCount(['orders', 'reviews']);

        if ($request->filled('search')) {
            $search = mb_strtolower((string) $request->string('search'));
            $query->where(fn(Builder $query): Builder => $query
                ->whereRaw('LOWER(email) LIKE ?', ['%' . $search . '%'])
                ->orWhereRaw('LOWER(first_name) LIKE ?', ['%' . $search . '%'])
                ->orWhereRaw('LOWER(last_name) LIKE ?', ['%' . $search . '%']));
        }

        return CustomerResource::collection($query->latest()->paginate($request->perPage()));
    }

    public function store(StoreCustomerRequest $request): CustomerResource
    {
        $data = $request->validated();
        $data['role'] = UserRole::User->value;
        $customer = User::query()->create($data);

        return new CustomerResource($customer->loadCount(['orders', 'reviews']));
    }

    public function show(User $customer): CustomerResource
    {
        abort_unless($customer->role === UserRole::User->value, 404);

        return new CustomerResource($customer->loadCount(['orders', 'reviews']));
    }

    public function update(UpdateCustomerRequest $request, User $customer): CustomerResource
    {
        abort_unless($customer->role === UserRole::User->value, 404);

        $data = $request->validated();
        if (($data['password'] ?? null) === null) {
            unset($data['password']);
        }

        $customer->fill($data)->save();

        return new CustomerResource($customer->refresh()->loadCount(['orders', 'reviews']));
    }

    public function destroy(User $customer): Response
    {
        abort_unless($customer->role === UserRole::User->value, 404);
        $customer->delete();

        return response()->noContent();
    }
}
