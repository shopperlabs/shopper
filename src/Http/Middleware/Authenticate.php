<?php

namespace Shopper\Framework\Http\Middleware;

use Closure;
use Illuminate\Auth\AuthenticationException;
use Illuminate\Auth\Middleware\Authenticate as BaseAuthenticationMiddleware;
use Shopper\Framework\Exceptions\AuthenticationException as ShopperAuthenticationException;

class Authenticate extends BaseAuthenticationMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @param  string[]  ...$guards
     * @return mixed
     *
     * @throws \Illuminate\Auth\AuthenticationException
     */
    public function handle($request, Closure $next, ...$guards)
    {
        try {
            return parent::handle($request, $next, ...$guards);
        } catch (AuthenticationException $e) {
            throw new ShopperAuthenticationException('Unauthenticated.', $e->guards());
        }
    }
}
