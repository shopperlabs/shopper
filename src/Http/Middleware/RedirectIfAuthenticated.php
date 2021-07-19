<?php

namespace Shopper\Framework\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class RedirectIfAuthenticated
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     *
     * @return mixed
     */
    public function handle(Request $request, Closure $next)
    {
        if (Auth::guard(config('shopper.auth.guard'))->check()) {
            return redirect()->route('shopper.dashboard');
        }

        return $next($request);
    }
}
