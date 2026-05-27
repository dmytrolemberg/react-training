<?php

declare(strict_types = 1);

namespace App\Support\Auth;

use App\Models\User\User;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\ValidationException;

trait AuthenticatesSpaUsers
{
    protected function authenticateSpaUser(Request $request, string $email, string $password): User
    {
        $user = User::query()->where('email', $email)->first();

        if (!$user instanceof User || !Hash::check($password, $user->password)) {
            throw ValidationException::withMessages(['email' => ['These credentials do not match our records.']]);
        }

        Auth::guard('web')->login($user);
        $request->session()->regenerate();

        return $user;
    }

    protected function logoutSpaUser(Request $request): Response
    {
        Auth::guard('web')->logout();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return response()->noContent();
    }

    protected function authenticatedUser(Request $request): User
    {
        $user = $request->user();

        if (!$user instanceof User) {
            abort(Response::HTTP_UNAUTHORIZED);
        }

        return $user;
    }
}
