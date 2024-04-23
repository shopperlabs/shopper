<?php

declare(strict_types=1);

namespace Shopper\Livewire\Pages\Auth;

use Illuminate\Auth\Passwords\PasswordBroker;
use Illuminate\Contracts\View\View;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Password;
use Illuminate\Validation\Rules\Password as PasswordRule;
use Livewire\Attributes\Layout;
use Livewire\Component;
use Shopper\Facades\Shopper;

#[Layout('shopper::components.layouts.base')]
class ResetPassword extends Component
{
    public ?string $token = null;

    public string $email = '';

    public string $password = '';

    public function mount(?string $token = null): void
    {
        $this->email = request()->query('email', '');
        $this->token = $token;
    }

    public function resetPassword(): void
    {
        $this->validate([
            'token' => 'required',
            'email' => ['required', 'email'],
            'password' => [
                'required',
                PasswordRule::min(8)
                    ->numbers()
                    ->symbols()
                    ->mixedCase(),
            ],
        ]);

        $response = $this->broker()->reset(
            credentials: [
                'token' => $this->token,
                'email' => $this->email,
                'password' => $this->password,
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

        $this->addError('email', trans($response));
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
