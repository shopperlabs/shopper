<?php

namespace Shopper\Framework\Http\Livewire\Account;

use Livewire\Component;
use Illuminate\Validation\Rules;
use Illuminate\Support\Facades\Hash;

class Password extends Component
{
    public string $current_password = '';

    public string $password = '';

    public string $password_confirmation = '';

    public function save(): void
    {
        $this->validate($this->rules());

        $user = auth()->user();

        if (Hash::check($this->current_password, $user->password)) {
            $user->update(['password' => Hash::make($this->password)]);

            $this->current_password = '';
            $this->password = '';
            $this->password_confirmation = '';

            $this->notify([
                'title' => __('Password Changed!'),
                'message' => __('You have been successfully updated your password!'),
            ]);
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

    public function render()
    {
        return view('shopper::livewire.account.password');
    }
}
