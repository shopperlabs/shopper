<?php

namespace Shopper\Actions;

use Closure;
use Illuminate\Validation\ValidationException;
use Shopper\Facades\Shopper;
use Symfony\Component\HttpFoundation\Response;

final class AttemptToAuthenticate
{
    public function handle(array $request, Closure $next): Response
    {
        if (! Shopper::auth()->attempt([
            'email' => $request['email'],
            'password' => $request['password'],
        ], $request['remember'])) {
            $this->throwFailedAuthenticationException();
        }

        return $next(request());
    }

    protected function throwFailedAuthenticationException(): void
    {
        throw ValidationException::withMessages([
            'email' => __('shopper::messages.login.failed'),
        ]);
    }
}
