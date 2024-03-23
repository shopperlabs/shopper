<?php

declare(strict_types=1);

namespace Shopper\Http\Middleware;

use Illuminate\Auth\Middleware\Authenticate as Middleware;

final class Authenticate extends Middleware
{
    protected function authenticate($request, array $guards): void
    {
        $guardName = config('shopper.auth.guard');
        $guard = $this->auth->guard($guardName);

        if (! $guard->check()) {
            $this->unauthenticated($request, $guards);

            return;
        }

        $this->auth->shouldUse($guardName);
    }

    protected function redirectTo($request): string
    {
        return route('shopper.login');
    }
}
