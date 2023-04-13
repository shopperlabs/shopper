<?php

declare(strict_types=1);

namespace Shopper\Framework\Actions;

use Closure;
use Illuminate\Contracts\Auth\StatefulGuard;
use Illuminate\Http\Request;
use Illuminate\Validation\ValidationException;
use Shopper\Framework\Services\TwoFactor\LoginRateLimiter;
use Shopper\Framework\Shopper;

final class AttemptToAuthenticate
{
    public function __construct(protected StatefulGuard $guard, protected LoginRateLimiter $limiter)
    {
    }

    public function handle(Request $request, Closure $next)
    {
        if ($this->guard->attempt(
            $request->only(Shopper::username(), 'password'),
            $request->filled('remember')
        )
        ) {
            return $next($request);
        }

        $this->throwFailedAuthenticationException($request);
    }

    protected function throwFailedAuthenticationException(Request $request): void
    {
        $this->limiter->increment($request);

        throw ValidationException::withMessages([Shopper::username() => [trans('auth.failed')]]);
    }
}
