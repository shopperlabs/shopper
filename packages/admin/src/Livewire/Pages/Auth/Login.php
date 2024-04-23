<?php

declare(strict_types=1);

namespace Shopper\Livewire\Pages\Auth;

use DanHarrin\LivewireRateLimiting\Exceptions\TooManyRequestsException;
use DanHarrin\LivewireRateLimiting\WithRateLimiting;
use Illuminate\Contracts\View\View;
use Illuminate\Routing\Pipeline;
use Illuminate\Validation\ValidationException;
use Livewire\Attributes\Layout;
use Livewire\Component;
use Shopper\Actions\AttemptToAuthenticate;
use Shopper\Actions\RedirectIfTwoFactorAuthenticatable;
use Shopper\Contracts\LoginResponse;

#[Layout('shopper::components.layouts.base')]
final class Login extends Component
{
    use WithRateLimiting;

    public string $email = '';

    public string $password = '';

    public bool $remember = false;

    public function authenticate()
    {
        $this->validate([
            'email' => ['required', 'email'],
            'password' => 'required',
        ]);

        [$throwable] = useTryCatch(fn () => $this->rateLimit(5));

        if ($throwable instanceof TooManyRequestsException) {
            throw ValidationException::withMessages([
                'email' => __('shopper::pages/auth.login.throttled', [
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
            ->title(__('shopper::pages/auth.login.title'));
    }
}
