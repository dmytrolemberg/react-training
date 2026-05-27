<?php

declare(strict_types = 1);

namespace App\Client\Controllers\Api\V1;

use Illuminate\Http\Request;
use Illuminate\Http\Response;
use App\Http\Controllers\Controller;
use App\Support\Auth\AuthenticatesSpaUsers;
use App\Client\Requests\Api\V1\Auth\LoginRequest;
use App\Client\Resources\Api\V1\Auth\UserResource;

class AuthController extends Controller
{
    use AuthenticatesSpaUsers;

    public function login(LoginRequest $request): UserResource
    {
        $user = $this->authenticateSpaUser($request, $request->email(), $request->password());

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
