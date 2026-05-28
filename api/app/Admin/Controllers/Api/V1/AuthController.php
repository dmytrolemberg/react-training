<?php

declare(strict_types = 1);

namespace App\Admin\Controllers\Api\V1;

use Illuminate\Http\Request;
use App\Models\User\UserRole;
use Illuminate\Http\Response;
use App\Http\Controllers\Controller;
use App\Support\Auth\AuthenticatesSpaUsers;
use Illuminate\Validation\ValidationException;
use App\Admin\Requests\Api\V1\Auth\LoginRequest;
use App\Admin\Resources\Api\V1\Auth\UserResource;

class AuthController extends Controller
{
    use AuthenticatesSpaUsers;

    public function login(LoginRequest $request): UserResource
    {
        $user = $this->authenticateSpaUser($request, $request->email(), $request->password());

        if ($user->role !== UserRole::Admin->value) {
            $this->logoutSpaUser($request);

            throw ValidationException::withMessages(['email' => ['These credentials do not match our records.']]);
        }

        return new UserResource($user);
    }

    public function logout(Request $request): Response
    {
        return $this->logoutSpaUser($request);
    }

    public function user(Request $request): UserResource
    {
        return new UserResource($this->authenticatedUser($request));
    }
}
