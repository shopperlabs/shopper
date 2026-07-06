<?php

declare(strict_types=1);

namespace Shopper\Livewire\Components\Account;

use Filament\Actions\Action;
use Filament\Actions\Concerns\InteractsWithActions;
use Filament\Actions\Contracts\HasActions;
use Filament\Forms\Components\TextInput;
use Filament\Notifications\Notification;
use Filament\Schemas\Concerns\InteractsWithSchemas;
use Filament\Schemas\Contracts\HasSchemas;
use Filament\Support\Enums\Width;
use Illuminate\Contracts\View\View;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Validation\ValidationException;
use Laravel\Passkeys\Actions\DeletePasskey;
use Laravel\Passkeys\Contracts\PasskeyUser;
use Laravel\Passkeys\Passkey;
use Livewire\Attributes\Computed;
use Livewire\Attributes\On;
use Livewire\Component;
use Mckenziearts\Icons\Untitledui\Enums\Untitledui;
use Shopper\Traits\ConfirmsPasswords;
use Shopper\Traits\HandlesAuthorizationExceptions;

/**
 * @property-read PasskeyUser $user
 */
class Passkeys extends Component implements HasActions, HasSchemas
{
    use ConfirmsPasswords;
    use HandlesAuthorizationExceptions;
    use InteractsWithActions;
    use InteractsWithSchemas;

    public function addPasskeyAction(): Action
    {
        return Action::make('addPasskey')
            ->label(__('shopper::pages/auth.account.passkey_add'))
            ->modalWidth(Width::Large)
            ->modalHeading(__('shopper::pages/auth.account.passkey_add'))
            ->modalDescription(__('shopper::pages/auth.account.passkey_add_description'))
            ->modalSubmitActionLabel(__('shopper::forms.actions.save'))
            ->schema([
                TextInput::make('name')
                    ->label(__('shopper::forms.label.name'))
                    ->placeholder(__('shopper::pages/auth.account.passkey_name_placeholder'))
                    ->required()
                    ->maxLength(255),
            ])
            ->action(function (array $data): void {
                $this->ensurePasswordIsConfirmed();

                $this->dispatch('shopper-passkey-register', name: $data['name']);
            });
    }

    #[On('openAddPasskeyModal')]
    public function openAddPasskeyModal(): void
    {
        $this->ensurePasswordIsConfirmed();

        $this->mountAction('addPasskey');
    }

    #[On('passkeyRegistered')]
    public function passkeyRegistered(): void
    {
        unset($this->passkeys);

        Notification::make()
            ->title(__('shopper::notifications.passkeys.registered'))
            ->success()
            ->send();
    }

    #[On('passkeyRegistrationFailed')]
    public function passkeyRegistrationFailed(string $message): void
    {
        Notification::make()
            ->title(__('shopper::notifications.passkeys.failed'))
            ->body($message)
            ->danger()
            ->send();
    }

    public function deletePasskeyAction(): Action
    {
        return Action::make('deletePasskey')
            ->label(__('shopper::forms.actions.delete'))
            ->icon(Untitledui::Trash03)
            ->color('danger')
            ->iconButton()
            ->requiresConfirmation()
            ->modalWidth(Width::Medium)
            ->modalIcon(Untitledui::Trash03)
            ->modalHeading(__('shopper::pages/auth.account.passkey_delete'))
            ->modalDescription(__('shopper::pages/auth.account.passkey_delete_confirmation'))
            ->modalSubmitActionLabel(__('shopper::forms.actions.delete'))
            ->schema([
                TextInput::make('password')
                    ->label(__('shopper::forms.label.password'))
                    ->password()
                    ->revealable()
                    ->inlineSuffix()
                    ->required()
                    ->placeholder(__('shopper::forms.placeholder.password')),
            ])
            ->action(function (array $arguments, array $data): void {
                if (! $this->confirmPassword($data['password'])) {
                    throw ValidationException::withMessages([
                        'mountedActions.0.data.password' => __('shopper::notifications.auth.password'),
                    ]);
                }

                /** @var Passkey $passkey */
                $passkey = $this->user->passkeys()->findOrFail($arguments['passkey'] ?? null);

                app(DeletePasskey::class)($this->user, $passkey);

                unset($this->passkeys);

                Notification::make()
                    ->title(__('shopper::notifications.passkeys.deleted'))
                    ->success()
                    ->send();
            });
    }

    #[Computed]
    public function user(): PasskeyUser
    {
        /** @var PasskeyUser */
        return shopper()->auth()->user();
    }

    /**
     * @return Collection<int, Passkey>
     */
    #[Computed]
    public function passkeys(): Collection
    {
        return $this->user->passkeys()->latest()->get();
    }

    public function render(): View
    {
        return view('shopper::livewire.components.account.passkeys');
    }
}
