<?php

declare(strict_types=1);

namespace Shopper\Http\Livewire\Components\Account;

use Filament\Notifications\Notification;
use Illuminate\Contracts\Auth\Authenticatable;
use Illuminate\Contracts\View\View;
use Livewire\Component;
use Shopper\Actions\DisableTwoFactorAuthentication;
use Shopper\Actions\EnableTwoFactorAuthentication;
use Shopper\Actions\GenerateNewRecoveryCodes;
use Shopper\Facades\Shopper;
use Shopper\Traits\ConfirmsPasswords;

class TwoFactor extends Component
{
    use ConfirmsPasswords;

    public bool $showingQrCode = false;

    public bool $showingRecoveryCodes = false;

    public function enableTwoFactorAuthentication(EnableTwoFactorAuthentication $enable): void
    {
        if (config('shopper.auth.2fa_enabled')) {
            $this->ensurePasswordIsConfirmed();
        }

        $enable(Shopper::auth()->user());

        $this->showingQrCode = true;
        $this->showingRecoveryCodes = true;

        Notification::make()
            ->body(__('You have successfully enabled two-factor authentication.'))
            ->success()
            ->send();
    }

    public function showRecoveryCodes(): void
    {
        if (config('shopper.auth.2fa_enabled')) {
            $this->ensurePasswordIsConfirmed();
        }

        $this->showingRecoveryCodes = true;
    }

    public function regenerateRecoveryCodes(GenerateNewRecoveryCodes $generate): void
    {
        if (config('shopper.auth.2fa_enabled')) {
            $this->ensurePasswordIsConfirmed();
        }

        $generate(Shopper::auth()->user());

        $this->showingRecoveryCodes = true;

        Notification::make()
            ->body(__('You have successfully regenerated your two-factor authentication recovery codes.'))
            ->success()
            ->send();
    }

    public function disableTwoFactorAuthentication(DisableTwoFactorAuthentication $disable): void
    {
        if (config('shopper.auth.2fa_enabled')) {
            $this->ensurePasswordIsConfirmed();
        }

        $disable(Shopper::auth()->user());

        Notification::make()
            ->body(__('You have successfully disabled two-factor authentication.'))
            ->success()
            ->send();
    }

    public function getUserProperty(): Authenticatable
    {
        return Shopper::auth()->user();
    }

    public function getEnabledProperty(): bool
    {
        return ! empty($this->user->two_factor_secret);
    }

    public function render(): View
    {
        return view('shopper::livewire.account.two-factor');
    }
}
