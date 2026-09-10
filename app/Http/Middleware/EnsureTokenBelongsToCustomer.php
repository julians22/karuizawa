<?php

namespace App\Http\Middleware;

use App\Models\Customer;
use Closure;
use Illuminate\Http\Request;

class EnsureTokenBelongsToCustomer
{
    /**
     * Reject sanctum tokens that don't belong to a Customer (e.g. staff/User tokens).
     */
    public function handle(Request $request, Closure $next)
    {
        if (!$request->user() instanceof Customer) {
            return response()->json([
                'success' => false,
                'message' => 'Unauthenticated.',
            ], 401);
        }

        return $next($request);
    }
}
