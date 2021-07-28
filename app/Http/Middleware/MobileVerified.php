<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class MobileVerified
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
        if (auth()->user()->mobile and !auth()->user()->mobile_verified_at) {
            return redirect()->route('verify-mobile-form');
        }

        return $next($request);
    }
}
