<?php

namespace Shopper\Framework\Http\Middleware;

use Closure;
use Illuminate\Support\Facades\Auth;

class RedirectIfAuthenticated
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
        if (Auth::guard(config('shopper.auth.guard'))->check()) {
            return redirect()->route('shopper.dashboard');
        }

        return $next($request);
    }
}
