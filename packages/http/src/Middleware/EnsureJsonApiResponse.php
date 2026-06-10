<?php

declare(strict_types=1);

namespace Shopper\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

final class EnsureJsonApiResponse
{
    public function handle(Request $request, Closure $next): Response
    {
        $request->headers->set('Accept', 'application/vnd.api+json');

        $response = $next($request);

        $contentType = (string) $response->headers->get('Content-Type', '');

        if (str_contains($contentType, 'json')) {
            $response->headers->set('Content-Type', 'application/vnd.api+json');
        }

        return $response;
    }
}
