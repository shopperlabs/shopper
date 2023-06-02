<?php

declare(strict_types=1);

namespace Shopper\Framework\Traits;

use Illuminate\Contracts\Auth\StatefulGuard;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\ValidationException;
use Shopper\Framework\Actions\ConfirmPassword;

trait ConfirmsPasswords
{
    /**
     * Indicates if the user's password is being confirmed.
     */
    public bool $confirmingPassword = false;

    /**
     * The ID of the operation being confirmed.
     */
    public ?string $confirmableId = null;

    /**
     * The user's password.
     */
    public string $confirmablePassword = '';

    /**
     * Start confirming the user's password.
     */
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

    /**
     * Stop confirming the user's password.
     */
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
        if (! app(ConfirmPassword::class)(app(StatefulGuard::class), Auth::user(), $this->confirmablePassword)) {
            throw ValidationException::withMessages(['confirmable_password' => [__('This password does not match our records.')]]);
        }

        session(['auth.password_confirmed_at' => time()]);

        $this->dispatchBrowserEvent('password-confirmed', [
            'id' => $this->confirmableId,
        ]);

        $this->stopConfirmingPassword();
    }

    /**
     * Ensure that the user's password has been recently confirmed.
     */
    protected function ensurePasswordIsConfirmed(int $maximumSecondsSinceConfirmation = null)
    {
        $maximumSecondsSinceConfirmation = $maximumSecondsSinceConfirmation ?? config('auth.password_timeout', 900);

        return $this->passwordIsConfirmed($maximumSecondsSinceConfirmation) ? null : abort(403);
    }

    /**
     * Determine if the user's password has been recently confirmed.
     */
    protected function passwordIsConfirmed(int $maximumSecondsSinceConfirmation = null): bool
    {
        $maximumSecondsSinceConfirmation = $maximumSecondsSinceConfirmation ?? config('auth.password_timeout', 900);

        return time() - session('auth.password_confirmed_at', 0) < $maximumSecondsSinceConfirmation;
    }
}
