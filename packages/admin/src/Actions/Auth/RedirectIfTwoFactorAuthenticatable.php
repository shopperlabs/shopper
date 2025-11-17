<?php

declare(strict_types=1);

namespace Shopper\Actions\Auth;

use Closure;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\ValidationException;
use Shopper\Core\Models\User;
use Shopper\Facades\Shopper;
use Shopper\Traits\TwoFactorAuthenticatable;

class RedirectIfTwoFactorAuthenticatable
{
    /**
     * @param  array<array-key, mixed>  $data
     * @return JsonResponse|RedirectResponse
     */
    public function handle(array $data, Closure $next)
    {
        $user = $this->validateCredentials($data);

        if ($user->two_factor_secret &&
            in_array(TwoFactorAuthenticatable::class, class_uses_recursive($user))) {
            return $this->twoFactorChallengeResponse($user, $data['remember']);
        }

        return $next($data);
    }

    /**
     * @param  array<string, mixed>  $request
     */
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

    protected function twoFactorChallengeResponse(User $user, bool $remember): JsonResponse|RedirectResponse
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
