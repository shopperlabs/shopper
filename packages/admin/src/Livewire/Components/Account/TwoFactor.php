<?php

declare(strict_types=1);

namespace Shopper\Livewire\Components\Account;

use Filament\Actions\Concerns\InteractsWithActions;
use Filament\Actions\Contracts\HasActions;
use Filament\Notifications\Notification;
use Filament\Schemas\Concerns\InteractsWithSchemas;
use Filament\Schemas\Contracts\HasSchemas;
use Illuminate\Contracts\View\View;
use Livewire\Attributes\Computed;
use Livewire\Attributes\On;
use Livewire\Component;
use Shopper\Actions\Auth\DisableTwoFactorAuthentication;
use Shopper\Actions\Auth\EnableTwoFactorAuthentication;
use Shopper\Actions\Auth\GenerateNewRecoveryCodes;
use Shopper\Contracts\HasStoreAuthentication;
use Shopper\Contracts\HasStoreAuthenticationRecovery;
use Shopper\Traits\ConfirmsPasswords;
use Shopper\Traits\HandlesAuthorizationExceptions;

/**
 * @property-read HasStoreAuthentication&HasStoreAuthenticationRecovery $user
 */
class TwoFactor extends Component implements HasActions, HasSchemas
{
    use ConfirmsPasswords;
    use HandlesAuthorizationExceptions;
    use InteractsWithActions;
    use InteractsWithSchemas;

    public bool $showingQrCode = false;

    public bool $showingRecoveryCodes = false;

    #[On('enableTwoFactorAuthentication')]
    public function enableTwoFactorAuthentication(EnableTwoFactorAuthentication $enable): void
    {
        if (config('shopper.auth.2fa_enabled')) {
            $this->ensurePasswordIsConfirmed();
        }

        $enable($this->user);

        $this->showingQrCode = true;
        $this->showingRecoveryCodes = true;

        Notification::make()
            ->title(__('shopper::notifications.users_roles.two_factor_enabled'))
            ->success()
            ->send();
    }

    #[On('showRecoveryCodes')]
    public function showRecoveryCodes(): void
    {
        if (config('shopper.auth.2fa_enabled')) {
            $this->ensurePasswordIsConfirmed();
        }

        $this->showingRecoveryCodes = true;
    }

    #[On('regenerateRecoveryCodes')]
    public function regenerateRecoveryCodes(GenerateNewRecoveryCodes $generate): void
    {
        if (config('shopper.auth.2fa_enabled')) {
            $this->ensurePasswordIsConfirmed();
        }

        $generate($this->user);

        $this->showingRecoveryCodes = true;

        Notification::make()
            ->title(__('shopper::notifications.users_roles.two_factor_generate'))
            ->success()
            ->send();
    }

    #[On('disableTwoFactorAuthentication')]
    public function disableTwoFactorAuthentication(DisableTwoFactorAuthentication $disable): void
    {
        if (config('shopper.auth.2fa_enabled')) {
            $this->ensurePasswordIsConfirmed();
        }

        $disable($this->user);

        $this->showingQrCode = false;
        $this->showingRecoveryCodes = false;

        Notification::make()
            ->title(__('shopper::notifications.users_roles.two_factor_disabled'))
            ->success()
            ->send();
    }

    #[Computed]
    public function user(): HasStoreAuthentication
    {
        /** @var HasStoreAuthentication */
        return shopper()->auth()->user();
    }

    #[Computed]
    public function enabled(): bool
    {
        return $this->user->getStoreAuthenticationSecret() !== null;
    }

    public function render(): View
    {
        return view('shopper::livewire.components.account.two-factor');
    }
}
