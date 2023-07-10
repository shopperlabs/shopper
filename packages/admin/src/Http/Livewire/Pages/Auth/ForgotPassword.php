<?php

declare(strict_types=1);

namespace Shopper\Http\Livewire\Pages\Auth;

use Illuminate\Auth\Passwords\PasswordBroker;
use Illuminate\Contracts\View\View;
use Illuminate\Support\Facades\Password;
use Livewire\Component;
use Shopper\Core\Rules\RealEmailValidator;

final class ForgotPassword extends Component
{
    public string $email = '';

    public function sendResetPasswordLink(): void
    {
        $this->validate([
            'email' => ['required', 'email', new RealEmailValidator()],
        ]);

        $response = $this->broker()->sendResetLink(['email' => $this->email]);

        if (Password::RESET_LINK_SENT == $response) {
            session()->flash('success', trans($response));

            return;
        }

        $this->addError('email', trans($response));
    }

    public function broker(): PasswordBroker
    {
        return Password::broker();
    }

    public function render(): View
    {
        return view('shopper::livewire.pages.auth.forgot-password')
            ->layout('shopper::components.layouts.base', [
                'title' => __('shopper::pages/auth.email.title'),
            ]);
    }
}
