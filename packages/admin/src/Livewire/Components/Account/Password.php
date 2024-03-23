<?php

declare(strict_types=1);

namespace Shopper\Livewire\Components\Account;

use Filament\Notifications\Notification;
use Illuminate\Contracts\View\View;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules;
use Livewire\Component;
use Shopper\Facades\Shopper;

class Password extends Component
{
    public string $current_password = '';

    public string $password = '';

    public string $password_confirmation = '';

    public function save(): void
    {
        $this->validate($this->rules());

        $user = Shopper::auth()->user();

        // @phpstan-ignore-next-line
        if (Hash::check($this->current_password, $user->password)) {
            $user->update(['password' => Hash::make($this->password)]);

            $this->reset('current_password', 'password', 'password_confirmation');

            Notification::make()
                ->body(__('shopper::notifications.users_roles.password_changed'))
                ->success()
                ->send();
        } else {
            session()->flash('error', __('shopper::notifications.users_roles.current_password'));
        }
    }

    public function rules(): array
    {
        return [
            'current_password' => 'required',
            'password' => [
                'required',
                'confirmed',
                Rules\Password::min(8)->numbers()->symbols()->mixedCase(),
            ],
        ];
    }

    public function render(): View
    {
        return view('shopper::livewire.account.password');
    }
}
