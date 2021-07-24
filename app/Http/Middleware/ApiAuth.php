<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class ApiAuth
{
    /**
     * Handle an incoming request.
     *
     * @param \Illuminate\Http\Request $request
     * @param \Closure $next
     * @return mixed
     */
    public function handle(Request $request, Closure $next)
    {
        if ($request->get('api_key') != config('auth.api_key')) {
            return response([
                'status' => 'error',
                'message' => 'Access denied',
            ], 403);
        }

        return $next($request);
    }
}
