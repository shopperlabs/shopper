<?php

declare(strict_types=1);

namespace Shopper\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class HasConfiguration
{
    public function handle(Request $request, Closure $next)
    {
        if (shopper_setting('email') && shopper_setting('street_address')) {
            if ($request->ajax() || $request->wantsJson()) {
                return response()->json(['setup' => true]);
            }

            return redirect()->route('shopper.dashboard');
        }

        return $next($request);
    }
}
