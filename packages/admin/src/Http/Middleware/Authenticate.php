<?php

declare(strict_types=1);

namespace Shopper\Http\Middleware;

use Illuminate\Auth\AuthenticationException;
use Illuminate\Auth\Middleware\Authenticate as Middleware;

class Authenticate extends Middleware
{
    /**
     * @param  \Illuminate\Http\Request  $request
     * @param  array<int, string>  $guards
     *
     * @throws AuthenticationException
     */
    protected function authenticate($request, array $guards): void
    {
        $guardName = config('shopper.auth.guard');
        $guard = $this->auth->guard($guardName);

        if (! $guard->check()) {
            $this->unauthenticated($request, $guards);
        }

        $this->auth->shouldUse($guardName);
    }

    protected function redirectTo($request): string
    {
        return route('shopper.login');
    }
}
