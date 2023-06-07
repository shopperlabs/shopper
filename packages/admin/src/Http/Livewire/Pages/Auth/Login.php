<?php

namespace Shopper\Http\Livewire\Pages\Auth;

use DanHarrin\LivewireRateLimiting\Exceptions\TooManyRequestsException;
use DanHarrin\LivewireRateLimiting\WithRateLimiting;
use Illuminate\Contracts\View\View;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Validation\ValidationException;
use Livewire\Component;
use Shopper\Core\Rules\RealEmailValidator;
use Shopper\Core\Shopper;

class Login extends Component
{
    use AuthorizesRequests;
    use WithRateLimiting;

    public string $email = '';

    public string $password = '';

    public bool $remember = false;

    public function authenticate(): void
    {
        $this->validate([
            'email' => ['required', 'email', new RealEmailValidator()],
            'password' => 'required',
            'remember' => 'nullable',
        ]);

        [$throwable, ] = useTryCatch(fn () =>$this->rateLimit(5));

        if ($throwable instanceof TooManyRequestsException) {
            throw ValidationException::withMessages([
                'email' => __('shopper::messages.login.throttled', [
                    'seconds' => $throwable->secondsUntilAvailable,
                    'minutes' => ceil($throwable->secondsUntilAvailable / 60),
                ]),
            ]);
        }

        if (! Shopper::auth()->attempt([
            'email' => $this->email,
            'password' => $this->email,
        ], $this->remember)) {
            throw ValidationException::withMessages([
                'email' => __('shopper::messages.login.failed'),
            ]);
        }

        session()->regenerate();

        $this->redirectRoute('shopper.dashboard');
    }

    public function render(): View
    {
        return view('shopper::livewire.pages.auth.login')
            ->layout('shopper::components.layouts.base', [
                'title' => __('shopper::pages/auth.login.title'),
            ]);
    }
}
