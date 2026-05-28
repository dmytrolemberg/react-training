<?php

declare(strict_types = 1);

namespace App\Admin\Controllers\Api\V1\User;

use App\Models\User\User;
use Illuminate\Http\Request;
use App\Models\User\UserRole;
use Illuminate\Http\Response;
use App\Http\Controllers\Controller;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Validation\ValidationException;
use App\Admin\Resources\Api\V1\User\AdminUserResource;
use App\Admin\Requests\Api\V1\User\AdminUserIndexRequest;
use App\Admin\Requests\Api\V1\User\StoreAdminUserRequest;
use App\Admin\Requests\Api\V1\User\UpdateAdminUserRequest;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;

class AdminUserController extends Controller
{
    public function index(AdminUserIndexRequest $request): AnonymousResourceCollection
    {
        $query = User::query()->where('role', UserRole::Admin->value);

        if ($request->filled('search')) {
            $search = mb_strtolower((string) $request->string('search'));
            $query->where(fn(Builder $query): Builder => $query
                ->whereRaw('LOWER(email) LIKE ?', ['%' . $search . '%'])
                ->orWhereRaw('LOWER(first_name) LIKE ?', ['%' . $search . '%'])
                ->orWhereRaw('LOWER(last_name) LIKE ?', ['%' . $search . '%']));
        }

        return AdminUserResource::collection($query->latest()->paginate($request->perPage()));
    }

    public function store(StoreAdminUserRequest $request): AdminUserResource
    {
        $data = $request->validated();
        $data['role'] = UserRole::Admin->value;
        $admin = User::query()->create($data);

        return new AdminUserResource($admin);
    }

    public function show(User $adminUser): AdminUserResource
    {
        $this->ensureAdmin($adminUser);

        return new AdminUserResource($adminUser);
    }

    public function update(UpdateAdminUserRequest $request, User $adminUser): AdminUserResource
    {
        $this->ensureAdmin($adminUser);

        $data = $request->validated();
        if (($data['password'] ?? null) === null) {
            unset($data['password']);
        }

        $adminUser->fill($data)->save();

        return new AdminUserResource($adminUser->refresh());
    }

    public function destroy(Request $request, User $adminUser): Response
    {
        $this->ensureAdmin($adminUser);

        if ($request->user()?->getKey() === $adminUser->getKey()) {
            throw ValidationException::withMessages(['admin_user' => ['You cannot delete your own admin account.']]);
        }

        if (User::query()->where('role', UserRole::Admin->value)->count() <= 1) {
            throw ValidationException::withMessages(['admin_user' => ['You cannot delete the last admin account.']]);
        }

        $adminUser->delete();

        return response()->noContent();
    }

    private function ensureAdmin(User $user): void
    {
        abort_unless($user->role === UserRole::Admin->value, 404);
    }
}
