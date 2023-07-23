<?php

declare(strict_types=1);

namespace Shopper\Actions;

use Closure;
use Illuminate\Validation\ValidationException;
use Shopper\Facades\Shopper;

final class AttemptToAuthenticate
{
    public function handle(array $request, Closure $next)
    {
        $isLoggedIn = Shopper::auth()->attempt([
            'email' => $request['email'],
            'password' => $request['password'],
        ], $request['remember']);

        if (! $isLoggedIn) {
            $this->throwFailedAuthenticationException();
        }

        return $next($isLoggedIn);
    }

    protected function throwFailedAuthenticationException(): void
    {
        throw ValidationException::withMessages([
            'email' => __('shopper::messages.login.failed'),
        ]);
    }
}
