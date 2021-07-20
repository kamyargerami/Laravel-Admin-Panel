<?php

namespace App\Http\Middleware;

use Closure;

class Permission
{
    /**
     * Handle an incoming request.
     *
     * @param \Illuminate\Http\Request $request
     * @param \Closure $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
        if (!auth()->check()) {
            abort(403, 'برای دسترسی به این بخش ابتدا وارد شوید');
        }

        if (config('app.env') == 'production') {
            if (!auth()->user()->can($request->route()->getName())) {
                abort(403, 'شما دسترسی ورود به این بخش را ندارید');
            }
        }

        return $next($request);
    }
}
