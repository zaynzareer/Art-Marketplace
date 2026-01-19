<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class CheckScopes
{
    /**
     * Handle an incoming request.
     *
     * Validates that the user's token has at least one of the required scopes.
     * Works with Sanctum tokens that have scopes assigned.
     *
     * @param Request $request
     * @param Closure $next
     * @param string ...$scopes
     * @return Response
     */
    public function handle(Request $request, Closure $next, ...$scopes): Response
    {
        // If user not authenticated, let auth middleware handle it
        if (!$request->user()) {
            abort(401, 'Unauthenticated');
        }

        // Personal access tokens in Sanctum use tokenCan method
        // Check if token has at least one of the required scopes
        foreach ($scopes as $scope) {
            if ($request->user()->tokenCan($scope)) {
                return $next($request);
            }
        }

        // Token doesn't have required scopes
        return response()->json([
            'message' => 'Insufficient token permissions. Required scopes: ' . implode(', ', $scopes)
        ], 403);
    }
}
