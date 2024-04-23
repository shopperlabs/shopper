<?php

declare(strict_types=1);

namespace Shopper\Actions;

use Closure;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\ValidationException;
use Shopper\Facades\Shopper;
use Shopper\Traits\TwoFactorAuthenticatable;

class RedirectIfTwoFactorAuthenticatable
{
    public function handle(array $data, Closure $next)
    {
        $user = $this->validateCredentials($data);

        if (optional($user)->two_factor_secret &&
            in_array(TwoFactorAuthenticatable::class, class_uses_recursive($user))) {
            return $this->twoFactorChallengeResponse($user, $data['remember']);
        }

        return $next($data);
    }

    protected function validateCredentials(array $request)
    {
        $model = Shopper::auth()->getProvider()->getModel(); // @phpstan-ignore-line

        return tap($model::where('email', $request['email'])->first(), function ($user) use ($request): void {
            if (! $user || ! Hash::check(value: $request['password'], hashedValue: $user->password)) {
                $this->throwFailedAuthenticationException();
            }
        });
    }

    protected function throwFailedAuthenticationException(): void
    {
        throw ValidationException::withMessages([
            'email' => __('shopper::pages/auth.login.failed'),
        ]);
    }

    protected function twoFactorChallengeResponse($user, bool $remember)
    {
        request()->session()->put([
            'login.id' => $user->getKey(),
            'login.remember' => $remember,
        ]);

        return request()->wantsJson()
            ? response()->json(['two_factor' => true])
            : redirect()->route('shopper.two-factor.login');
    }
}
