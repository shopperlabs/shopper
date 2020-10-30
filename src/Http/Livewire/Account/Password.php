<?php

namespace Shopper\Framework\Http\Livewire\Account;

use Illuminate\Support\Facades\Hash;
use Livewire\Component;
use Shopper\Framework\Rules\Password as PasswordRules;

class Password extends Component
{
    public $current_password = '';
    public $password = '';
    public $password_confirmation = '';

    public function save()
    {
        $this->validate($this->rules());

        $user = auth()->user();

        if (Hash::check($this->current_password, $user->password)) {
            $user->update(['password' => Hash::make($this->password)]);

            $this->current_password = '';
            $this->password = '';
            $this->password_confirmation = '';

            $this->dispatchBrowserEvent('notify', [
                'title' => __('Password Changed!'),
                'message' => __("You have been successfully updated your password!")
            ]);
        } else {
            session()->flash('error', __("That is not your current password."));
        }
    }

    public function rules()
    {
        return [
            'current_password' => 'required',
            'password' => [
                'required',
                'min:8',
                'confirmed',
                new PasswordRules(),
            ],
        ];
    }

    public function render()
    {
        return view('shopper::livewire.account.password');
    }
}
