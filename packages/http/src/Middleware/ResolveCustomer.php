<?php

declare(strict_types=1);

namespace Shopper\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

final class ResolveCustomer
{
    public function handle(Request $request, Closure $next): Response
    {
        $request->attributes->set('shopper_customer', $request->user());

        return $next($request);
    }
}
