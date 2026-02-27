<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class EnsureRole
{
    public function handle(Request $request, Closure $next, string ...$roles): Response
    {
        $user = $request->user();

        if (!$user) {
            return response()->json([
                'error' => [
                    'code' => 'unauthenticated',
                    'message' => 'Authentication required.',
                    'details' => null,
                ],
            ], 401);
        }

        if (!in_array((string) $user->rol, $roles, true)) {
            return response()->json([
                'error' => [
                    'code' => 'forbidden',
                    'message' => 'Insufficient role permissions.',
                    'details' => null,
                ],
            ], 403);
        }

        return $next($request);
    }
}
