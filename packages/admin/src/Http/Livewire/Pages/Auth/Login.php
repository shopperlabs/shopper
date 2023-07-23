<?php

declare(strict_types=1);

namespace Shopper\Http\Livewire\Pages\Auth;

use DanHarrin\LivewireRateLimiting\Exceptions\TooManyRequestsException;
use DanHarrin\LivewireRateLimiting\WithRateLimiting;
use Illuminate\Contracts\View\View;
use Illuminate\Routing\Pipeline;
use Illuminate\Validation\ValidationException;
use Livewire\Component;
use Shopper\Actions\AttemptToAuthenticate;
use Shopper\Actions\RedirectIfTwoFactorAuthenticatable;
use Shopper\Contracts\LoginResponse;
use Shopper\Core\Rules\RealEmailValidator;

final class Login extends Component
{
    use WithRateLimiting;

    public string $email = '';

    public string $password = '';

    public bool $remember = false;

    public function authenticate()
    {
        $this->validate([
            'email' => ['required', 'email', new RealEmailValidator()],
            'password' => 'required',
            'remember' => 'nullable',
        ]);

        [$throwable] = useTryCatch(fn () => $this->rateLimit(5));

        if ($throwable instanceof TooManyRequestsException) {
            throw ValidationException::withMessages([
                'email' => __('shopper::messages.login.throttled', [
                    'seconds' => $throwable->secondsUntilAvailable,
                    'minutes' => ceil($throwable->secondsUntilAvailable / 60),
                ]),
            ]);
        }

        $request = [
            'email' => $this->email,
            'password' => $this->password,
            'remember' => $this->remember,
        ];

        return (new Pipeline(app()))
            ->send($request)
            ->through(array_filter([
                config('shopper.auth.2fa_enabled') ? RedirectIfTwoFactorAuthenticatable::class : null,
                AttemptToAuthenticate::class,
            ]))
            ->then(fn ($request) => app(LoginResponse::class));
    }

    public function render(): View
    {
        return view('shopper::livewire.pages.auth.login')
            ->layout('shopper::components.layouts.base', [
                'title' => __('shopper::pages/auth.login.title'),
            ]);
    }
}
