<?php

declare(strict_types=1);

namespace Shopper\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Shopper\Http\Contracts\ChannelResolver;
use Shopper\Http\Support\Vary;
use Symfony\Component\HttpFoundation\Response;

final class ResolveChannel
{
    public function __construct(private readonly ChannelResolver $resolver) {}

    public function handle(Request $request, Closure $next): Response
    {
        $request->attributes->set('shopper_channel', $this->resolver->resolve($request));

        $response = $next($request);

        Vary::add($response, (string) config('shopper.http.channel.header', 'X-Shopper-Channel'));

        return $response;
    }
}
