<?php

declare(strict_types=1);

namespace Shopper\Framework\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class RedirectIfAuthenticated
{
    public function handle(Request $request, Closure $next)
    {
        if (Auth::guard(config('shopper.auth.guard'))->check()) {
            return redirect()->route('shopper.dashboard');
        }

        return $next($request);
    }
}
