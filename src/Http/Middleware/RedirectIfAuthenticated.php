<?php

namespace Shopper\Framework\Http\Middleware;

use Closure;
use Illuminate\Support\Facades\Auth;
use Shopper\Framework\Shopper;

class RedirectIfAuthenticated
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @param  string|null  $guard
     * @return mixed
     */
    public function handle($request, Closure $next, $guard = null)
    {
        if (Auth::guard($guard)->check()) {
            return redirect(Shopper::prefix());
        }

        return $next($request);
    }
}
