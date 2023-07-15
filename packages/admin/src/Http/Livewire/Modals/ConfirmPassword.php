<?php

namespace Shopper\Http\Livewire\Modals;

use Illuminate\Contracts\View\View;
use Illuminate\Validation\ValidationException;
use LivewireUI\Modal\ModalComponent;
use Shopper\Facades\Shopper;
use Shopper\Actions\ConfirmPassword as ConfirmPasswordAction;

class ConfirmPassword extends ModalComponent
{
    public string $confirmablePassword = '';

    public string $action = '';

    public function mount(string $action): void
    {
        $this->action = $action;
    }

    public function confirmPassword(): void
    {
        if (! app(ConfirmPasswordAction::class)(Shopper::auth(), Shopper::auth()->user(), $this->confirmablePassword)) {
            throw ValidationException::withMessages([
                'confirmable_password' => [__('This password does not match our records.')],
            ]);
        }

        session(['auth.password_confirmed_at' => time()]);

        $this->stopConfirmingPassword();

        $this->emit($this->action);

        $this->closeModal();
    }

    public function stopConfirmingPassword(): void
    {
        $this->confirmablePassword = '';
    }

    public static function modalMaxWidth(): string
    {
        return 'lg';
    }

    public function render(): View
    {
        return view('shopper::livewire.modals.confirm-password');
    }
}
