<?php

declare(strict_types = 1);

namespace App\Admin\Middleware;

use App\Models\User\User;
use Illuminate\Http\Request;
use App\Models\User\UserRole;
use Symfony\Component\HttpFoundation\Response;

class EnsureUserHasRole
{
    /**
     * @param \Closure(Request): Response $next
     */
    public function handle(Request $request, \Closure $next, string $role): Response
    {
        $requiredRole = UserRole::tryFrom($role);
        $user         = $request->user();

        if (!$requiredRole instanceof UserRole || !$user instanceof User || $user->role !== $requiredRole->value) {
            abort(Response::HTTP_FORBIDDEN);
        }

        return $next($request);
    }
}
