<?php

declare(strict_types=1);

namespace Shopper\Framework\Actions;

use Closure;
use Illuminate\Contracts\Auth\StatefulGuard;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\ValidationException;
use Shopper\Framework\Services\TwoFactor\LoginRateLimiter;
use Shopper\Framework\Services\TwoFactor\TwoFactorAuthenticatable;
use Shopper\Framework\Shopper;

final class RedirectIfTwoFactorAuthenticatable
{
    public function __construct(protected StatefulGuard $guard, protected LoginRateLimiter $limiter)
    {
    }

    public function handle(Request $request, Closure $next): JsonResponse|RedirectResponse
    {
        $user = $this->validateCredentials($request);

        if (optional($user)->two_factor_secret &&
            in_array(TwoFactorAuthenticatable::class, class_uses_recursive($user))) {
            return $this->twoFactorChallengeResponse($request, $user);
        }

        return $next($request);
    }

    protected function validateCredentials(Request $request)
    {
        $model = $this->guard->getProvider()->getModel();

        return tap($model::where(Shopper::username(), $request->{Shopper::username()})->first(), function ($user) use ($request) {
            if (! $user || ! Hash::check($request->password, $user->password)) {
                $this->throwFailedAuthenticationException($request);
            }
        });
    }

    protected function throwFailedAuthenticationException(Request $request): void
    {
        $this->limiter->increment($request);

        throw ValidationException::withMessages([Shopper::username() => [trans('auth.failed')]]);
    }

    protected function twoFactorChallengeResponse(Request $request, $user): JsonResponse|RedirectResponse
    {
        $request->session()->put([
            'login.id' => $user->getKey(),
            'login.remember' => $request->filled('remember'),
        ]);

        return $request->wantsJson()
            ? response()->json(['two_factor' => true])
            : redirect()->route('shopper.two-factor.login');
    }
}
