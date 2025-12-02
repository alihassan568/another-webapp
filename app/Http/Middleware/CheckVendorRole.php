<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class CheckVendorRole
{
    /**
     * Handle an incoming request.
     * Ensure only users with 'business' role can access vendor-specific routes
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        $user = $request->user();

        // Check if user is authenticated
        if (!$user) {
            return response()->json([
                'success' => false,
                'message' => 'Unauthenticated'
            ], 401);
        }

        // Check if user has vendor/business role
        if ($user->role !== 'business') {
            return response()->json([
                'success' => false,
                'message' => 'Access denied. This feature is only available for vendors.'
            ], 403);
        }

        return $next($request);
    }
}
