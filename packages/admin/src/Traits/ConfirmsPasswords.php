<?php

declare(strict_types=1);

namespace Shopper\Traits;

use Illuminate\Validation\ValidationException;
use Shopper\Actions\ConfirmPassword;
use Shopper\Facades\Shopper;

trait ConfirmsPasswords
{
    public bool $confirmingPassword = false;

    public ?string $confirmableId = null;

    public string $confirmablePassword = '';

    public function startConfirmingPassword(string $confirmableId): void
    {
        $this->resetErrorBag();

        if ($this->passwordIsConfirmed()) {
            $this->dispatchBrowserEvent('password-confirmed', [
                'id' => $confirmableId,
            ]);

            return;
        }

        $this->confirmingPassword = true;
        $this->confirmableId = $confirmableId;
        $this->confirmablePassword = '';

        $this->dispatchBrowserEvent('confirming-password');
    }

    public function stopConfirmingPassword(): void
    {
        $this->confirmingPassword = false;
        $this->confirmableId = null;
        $this->confirmablePassword = '';
    }

    /**
     * Confirm the user's password.
     *
     * @throws ValidationException
     */
    public function confirmPassword(): void
    {
        if (! app(ConfirmPassword::class)(Shopper::auth(), Shopper::auth()->user(), $this->confirmablePassword)) {
            throw ValidationException::withMessages([
                'confirmable_password' => [__('This password does not match our records.')],
            ]);
        }

        session(['auth.password_confirmed_at' => time()]);

        $this->dispatchBrowserEvent('password-confirmed', [
            'id' => $this->confirmableId,
        ]);

        $this->stopConfirmingPassword();
    }

    protected function ensurePasswordIsConfirmed(int $maximumSecondsSinceConfirmation = null)
    {
        $maximumSecondsSinceConfirmation = $maximumSecondsSinceConfirmation ?? config('auth.password_timeout', 900);

        return $this->passwordIsConfirmed($maximumSecondsSinceConfirmation) ? null : abort(403);
    }

    protected function passwordIsConfirmed(int $maximumSecondsSinceConfirmation = null): bool
    {
        $maximumSecondsSinceConfirmation = $maximumSecondsSinceConfirmation ?? config('auth.password_timeout', 900);

        return (time() - session('auth.password_confirmed_at', 0)) < $maximumSecondsSinceConfirmation;
    }
}
