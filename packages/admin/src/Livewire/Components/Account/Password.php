<?php

declare(strict_types=1);

namespace Shopper\Livewire\Components\Account;

use Filament\Forms\Components;
use Filament\Forms\Concerns\InteractsWithForms;
use Filament\Forms\Contracts\HasForms;
use Filament\Forms\Form;
use Filament\Notifications\Notification;
use Illuminate\Contracts\View\View;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\ValidationException;
use Livewire\Component;
use Shopper\Components\Section;
use Shopper\Core\Models\User;

class Password extends Component implements HasForms
{
    use InteractsWithForms;

    public ?array $data = [];

    public function mount(): void
    {
        $this->form->fill();
    }

    public function form(Form $form): Form
    {
        return $form
            ->schema([
                Section::make(__('shopper::pages/auth.account.password_title'))
                    ->description(__('shopper::pages/auth.account.password_description'))
                    ->aside()
                    ->compact()
                    ->schema([
                        Components\TextInput::make('current_password')
                            ->label(__('shopper::layout.forms.label.current_password'))
                            ->password()
                            ->currentPassword()
                            ->revealable()
                            ->required(),
                        Components\TextInput::make('password')
                            ->label(__('shopper::layout.forms.label.new_password'))
                            ->helperText(__('shopper::pages/auth.account.password_helper_validation'))
                            ->password()
                            ->revealable()
                            ->required()
                            ->confirmed(),
                        Components\TextInput::make('password_confirmation')
                            ->label(__('shopper::layout.forms.label.confirm_password'))
                            ->password()
                            ->revealable()
                            ->required(),
                    ])
            ])
            ->statePath('data');
    }

    protected function onValidationError(ValidationException $exception): void
    {
        Notification::make()
            ->title($exception->getMessage())
            ->danger()
            ->send();
    }

    public function save(): void
    {
        /** @var User $user */
        $user = shopper()->auth()->user();
        $data = $this->form->getState();

        $user->update(['password' => Hash::make(value: $data['password'])]);

        Notification::make()
            ->title(__('shopper::notifications.users_roles.password_changed'))
            ->success()
            ->send();
    }

    public function render(): View
    {
        return view('shopper::livewire.components.account.password');
    }
}
