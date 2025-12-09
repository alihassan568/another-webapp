<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class CheckVendorBlocked
{
    /**
     * Handle an incoming request.
     * Check if the authenticated vendor user is blocked.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        // Check if user is authenticated
        if ($request->user()) {
            // Check if user is a vendor (business role)
            if ($request->user()->role === 'business') {
                // Check if vendor is blocked
                if ($request->user()->blocked_at !== null) {
                    return response()->json([
                        'success' => false,
                        'message' => 'Your account has been blocked by the system administrator. Please contact support at info@anothergo.com for assistance.',
                        'error' => 'Account blocked',
                        'support_email' => 'info@anothergo.com'
                    ], 403);
                }
            }
        }

        return $next($request);
    }
}
