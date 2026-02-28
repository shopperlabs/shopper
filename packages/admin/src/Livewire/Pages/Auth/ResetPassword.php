<?php

declare(strict_types=1);

namespace Shopper\Livewire\Pages\Auth;

use Filament\Forms\Components\TextInput;
use Filament\Forms\Concerns\InteractsWithForms;
use Filament\Forms\Contracts\HasForms;
use Filament\Schemas\Schema;
use Illuminate\Auth\Passwords\PasswordBroker;
use Illuminate\Contracts\View\View;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Password;
use Illuminate\Validation\Rules\Password as PasswordRule;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Locked;
use Livewire\Component;
use Shopper\Facades\Shopper;

/**
 * @property-read Schema $form
 */
#[Layout('shopper::components.layouts.base')]
final class ResetPassword extends Component implements HasForms
{
    use InteractsWithForms;

    /** @var array<string, mixed>|null */
    public ?array $data = [];

    #[Locked]
    public ?string $token = null;

    public function mount(?string $token = null): void
    {
        /** @var string $email */
        $email = request()->query('email', '');

        $this->token = $token;
        $this->form->fill([
            'email' => $email,
        ]);
    }

    public function form(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextInput::make('email')
                    ->label(__('shopper::forms.label.email'))
                    ->email()
                    ->required()
                    ->autocomplete('email')
                    ->autofocus(),
                TextInput::make('password')
                    ->label(__('shopper::forms.label.new_password'))
                    ->password()
                    ->revealable()
                    ->inlineSuffix()
                    ->required()
                    ->confirmed()
                    ->rules([
                        PasswordRule::min(8)
                            ->numbers()
                            ->symbols()
                            ->mixedCase(),
                    ]),
                TextInput::make('password_confirmation')
                    ->label(__('shopper::forms.label.confirm_password'))
                    ->password()
                    ->revealable()
                    ->inlineSuffix()
                    ->required(),
            ])
            ->statePath('data');
    }

    public function resetPassword(): void
    {
        $data = $this->form->getState();

        $response = $this->broker()->reset(
            credentials: [
                'token' => $this->token,
                'email' => $data['email'],
                'password' => $data['password'],
            ],
            callback: function ($user, string $password): void {
                $user->password = Hash::make($password);
                $user->save();

                Shopper::auth()->login($user);
            }
        );

        if ($response === Password::PASSWORD_RESET) {
            $this->redirectRoute('shopper.dashboard');
        }

        $this->addError('data.email', trans($response));
    }

    public function broker(): PasswordBroker
    {
        return Password::broker();
    }

    public function render(): View
    {
        return view('shopper::livewire.pages.auth.reset-password')
            ->title(__('shopper::pages/auth.reset.title'));
    }
}
