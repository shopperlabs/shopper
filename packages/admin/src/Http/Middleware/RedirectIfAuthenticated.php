<?php

declare(strict_types=1);

namespace Shopper\Http\Middleware;

use Closure;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Shopper\Facades\Shopper;

final class RedirectIfAuthenticated
{
    public function handle(Request $request, Closure $next): RedirectResponse | Response
    {
        if (Shopper::auth()->check()) {
            return redirect()->route('shopper.dashboard');
        }

        return $next($request);
    }
}
