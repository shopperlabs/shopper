<?php

declare(strict_types=1);

namespace Shopper\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class RedirectIfAuthenticated
{
    public function handle(Request $request, Closure $next)
    {
        if (shopper()->auth()->check()) {
            return redirect()->route('shopper.dashboard');
        }

        return $next($request);
    }
}
