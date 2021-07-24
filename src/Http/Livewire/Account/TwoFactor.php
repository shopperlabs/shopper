<?php

namespace Shopper\Framework\Http\Livewire\Account;

use Livewire\Component;
use Shopper\Framework\Traits\ConfirmsPasswords;
use Shopper\Framework\Actions\GenerateNewRecoveryCodes;
use Shopper\Framework\Actions\EnableTwoFactorAuthentication;
use Shopper\Framework\Actions\DisableTwoFactorAuthentication;

class TwoFactor extends Component
{
    use ConfirmsPasswords;

    /**
     * Indicates if two factor authentication QR code is being displayed.
     */
    public bool $showingQrCode = false;

    /**
     * Indicates if two factor authentication recovery codes are being displayed.
     */
    public bool $showingRecoveryCodes = false;

    /**
     * Enable two factor authentication for the user.
     */
    public function enableTwoFactorAuthentication(EnableTwoFactorAuthentication $enable)
    {
        if (config('shopper.auth.2fa_enabled')) {
            $this->ensurePasswordIsConfirmed();
        }

        $enable(auth()->user());

        $this->showingQrCode = true;
        $this->showingRecoveryCodes = true;
    }

    /**
     * Display the user's recovery codes.
     */
    public function showRecoveryCodes()
    {
        if (config('shopper.auth.2fa_enabled')) {
            $this->ensurePasswordIsConfirmed();
        }

        $this->showingRecoveryCodes = true;
    }

    /**
     * Generate new recovery codes for the user.
     */
    public function regenerateRecoveryCodes(GenerateNewRecoveryCodes $generate)
    {
        if (config('shopper.auth.2fa_enabled')) {
            $this->ensurePasswordIsConfirmed();
        }

        $generate(auth()->user());

        $this->showingRecoveryCodes = true;
    }

    /**
     * Disable two factor authentication for the user.
     */
    public function disableTwoFactorAuthentication(DisableTwoFactorAuthentication $disable)
    {
        if (config('shopper.auth.2fa_enabled')) {
            $this->ensurePasswordIsConfirmed();
        }

        $disable(auth()->user());
    }

    /**
     * Get the current user of the application.
     */
    public function getUserProperty()
    {
        return auth()->user();
    }

    /**
     * Determine if two factor authentication is enabled.
     */
    public function getEnabledProperty(): bool
    {
        return ! empty($this->user->two_factor_secret);
    }

    public function render()
    {
        return view('shopper::livewire.account.two-factor');
    }
}
