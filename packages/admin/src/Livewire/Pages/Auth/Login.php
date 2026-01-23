<?php

declare(strict_types=1);

namespace Shopper\Livewire\Pages\Auth;

use DanHarrin\LivewireRateLimiting\Exceptions\TooManyRequestsException;
use DanHarrin\LivewireRateLimiting\WithRateLimiting;
use Illuminate\Contracts\View\View;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\ValidationException;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Locked;
use Livewire\Attributes\Validate;
use Livewire\Component;
use Shopper\Contracts\LoginResponse;
use Shopper\Contracts\TwoFactorAuthenticationProvider;
use Shopper\Facades\Shopper;
use Shopper\Traits\TwoFactorAuthenticatable;

#[Layout('shopper::components.layouts.base')]
final class Login extends Component
{
    use WithRateLimiting;

    #[Validate('required|email')]
    public string $email = '';

    #[Validate('required')]
    public string $password = '';

    public bool $remember = false;

    #[Locked]
    public ?string $challengedUserId = null;

    public string $code = '';

    public string $recoveryCode = '';

    public bool $useRecoveryCode = false;

    public function authenticate(): mixed
    {
        $this->validate();

        [$throwable] = useTryCatch(fn () => $this->rateLimit(5));

        if ($throwable instanceof TooManyRequestsException) {
            throw ValidationException::withMessages([
                'email' => __('shopper::pages/auth.login.throttled', [
                    'seconds' => $throwable->secondsUntilAvailable,
                    'minutes' => ceil($throwable->secondsUntilAvailable / 60),
                ]),
            ]);
        }

        $user = $this->validateCredentials();

        if ($this->challengedUserId && decrypt($this->challengedUserId) === (string) $user->getKey()) {
            return $this->verifyTwoFactorCode($user);
        }

        if ($this->shouldChallenge($user)) {
            $this->challengedUserId = encrypt((string) $user->getKey());

            return null;
        }

        return $this->loginUser($user);
    }

    public function resetChallenge(): void
    {
        $this->challengedUserId = null;
        $this->code = '';
        $this->recoveryCode = '';
        $this->useRecoveryCode = false;
        $this->resetValidation();
    }

    public function render(): View
    {
        return view('shopper::livewire.pages.auth.login')
            ->title(__('shopper::pages/auth.login.title'));
    }

    private function validateCredentials(): mixed
    {
        $model = Shopper::auth()->getProvider()->getModel(); // @phpstan-ignore-line

        $user = $model::where('email', $this->email)->first();

        if (! $user || ! Hash::check($this->password, $user->password)) {
            throw ValidationException::withMessages([
                'email' => __('shopper::pages/auth.login.failed'),
            ]);
        }

        return $user;
    }

    private function shouldChallenge(mixed $user): bool
    {
        return config('shopper.auth.2fa_enabled')
            && $user->two_factor_secret
            && in_array(TwoFactorAuthenticatable::class, class_uses_recursive($user));
    }

    private function verifyTwoFactorCode(mixed $user): mixed
    {
        if ($this->useRecoveryCode) {
            $validCode = collect($user->recoveryCodes())
                ->first(fn ($code): bool => hash_equals($this->recoveryCode, $code));

            if (! $validCode) {
                throw ValidationException::withMessages([
                    'recoveryCode' => __('The provided two factor recovery code was invalid.'),
                ]);
            }

            $user->replaceRecoveryCode($validCode);
        } else {
            $isValid = app(TwoFactorAuthenticationProvider::class)->verify(
                decrypt($user->two_factor_secret),
                $this->code,
            );

            if (! $isValid) {
                throw ValidationException::withMessages([
                    'code' => __('The provided two factor authentication code was invalid.'),
                ]);
            }
        }

        return $this->loginUser($user);
    }

    private function loginUser(mixed $user): mixed
    {
        Shopper::auth()->login($user, $this->remember);

        session()->regenerate();

        return app(LoginResponse::class);
    }
}
