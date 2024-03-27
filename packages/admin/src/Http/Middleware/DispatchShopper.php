<?php

declare(strict_types=1);

namespace Shopper\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Shopper\Events\LoadShopper;

class DispatchShopper
{
    public function handle(Request $request, Closure $next)
    {
        LoadShopper::dispatch();

        return $next($request);
    }
}
