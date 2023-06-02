<?php

namespace Shopper\Http\Middleware;

use Illuminate\Auth\Middleware\Authenticate as Middleware;

class Authenticate extends Middleware
{
    protected function authenticate($request, array $guards): void
    {
        $guardName = config('shopper.auth.guard');

        $this->auth->guard($guardName)->check()
            ? $this->auth->shouldUse($guardName)
            : $this->unauthenticated($request, $guards);
    }

    protected function redirectTo($request): string
    {
        return route('shopper.login');
    }
}
