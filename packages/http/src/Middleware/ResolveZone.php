<?php

declare(strict_types=1);

namespace Shopper\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Shopper\Http\Contracts\ZoneResolver;
use Symfony\Component\HttpFoundation\Response;

final class ResolveZone
{
    public function __construct(private readonly ZoneResolver $resolver) {}

    public function handle(Request $request, Closure $next): Response
    {
        $request->attributes->set('shopper_zone', $this->resolver->resolve($request));

        return $next($request);
    }
}
