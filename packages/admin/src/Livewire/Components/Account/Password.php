<?php

declare(strict_types=1);

namespace Shopper\Livewire\Components\Account;

use Filament\Actions\Concerns\InteractsWithActions;
use Filament\Actions\Contracts\HasActions;
use Filament\Forms\Components\TextInput;
use Filament\Notifications\Notification;
use Filament\Schemas\Concerns\InteractsWithSchemas;
use Filament\Schemas\Contracts\HasSchemas;
use Filament\Schemas\Schema;
use Illuminate\Contracts\View\View;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\ValidationException;
use Livewire\Component;
use Shopper\Components\Section;
use Shopper\Models\Contracts\ShopperUser;
use Shopper\Traits\HandlesAuthorizationExceptions;

/**
 * @property-read Schema $form
 */
class Password extends Component implements HasActions, HasSchemas
{
    use HandlesAuthorizationExceptions;
    use InteractsWithActions;
    use InteractsWithSchemas;

    /** @var array<string, mixed>|null */
    public ?array $data = [];

    public function mount(): void
    {
        $this->form->fill();
    }

    public function form(Schema $schema): Schema
    {
        return $schema
            ->components([
                Section::make(__('shopper::pages/auth.account.password_title'))
                    ->aside()
                    ->compact()
                    ->description(__('shopper::pages/auth.account.password_description'))
                    ->extraAttributes(['class' => 'sh-section-aside'])
                    ->schema([
                        TextInput::make('current_password')
                            ->label(__('shopper::forms.label.current_password'))
                            ->password()
                            ->currentPassword()
                            ->revealable()
                            ->inlineSuffix()
                            ->required(),
                        TextInput::make('password')
                            ->label(__('shopper::forms.label.new_password'))
                            ->helperText(__('shopper::pages/auth.account.password_helper_validation'))
                            ->password()
                            ->revealable()
                            ->inlineSuffix()
                            ->required()
                            ->confirmed(),
                        TextInput::make('password_confirmation')
                            ->label(__('shopper::forms.label.confirm_password'))
                            ->password()
                            ->revealable()
                            ->inlineSuffix()
                            ->required(),
                    ]),
            ])
            ->statePath('data');
    }

    public function save(): void
    {
        /** @var ShopperUser $user */
        $user = shopper()->auth()->user();

        $user->update(['password' => Hash::make(value: $this->form->getState()['password'])]);

        Notification::make()
            ->title(__('shopper::notifications.users_roles.password_changed'))
            ->success()
            ->send();
    }

    public function render(): View
    {
        return view('shopper::livewire.components.account.password');
    }

    protected function onValidationError(ValidationException $exception): void
    {
        Notification::make()
            ->title($exception->getMessage())
            ->danger()
            ->send();
    }
}
