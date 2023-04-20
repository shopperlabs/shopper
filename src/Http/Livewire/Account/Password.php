<?php

declare(strict_types=1);

namespace Shopper\Framework\Http\Livewire\Account;

use Illuminate\Contracts\View\View;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules;
use Livewire\Component;
use WireUi\Traits\Actions;

class Password extends Component
{
    use Actions;

    public string $current_password = '';

    public string $password = '';

    public string $password_confirmation = '';

    public function save(): void
    {
        $this->validate($this->rules());

        $user = Auth::user();

        if (Hash::check($this->current_password, $user->password)) {
            $user->update(['password' => Hash::make($this->password)]);

            $this->reset('current_password', 'password', 'password_confirmation');

            $this->notification()->success(__('Password Changed!'), __('You have been successfully updated your password!'));
        } else {
            session()->flash('error', __('That is not your current password.'));
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
