<?php

namespace Shopper\Framework\Http\Livewire\Account;

use Illuminate\Support\Facades\Hash;
use Livewire\Component;
use Shopper\Framework\Rules\Password as PasswordRules;

class Password extends Component
{
    /**
     * User current password.
     *
     * @var string
     */
    public $current_password;

    /**
     * User new password.
     *
     * @var string
     */
    public $password;

    /**
     * Password confirmation.
     *
     * @var string
     */
    public $password_confirmation;

    /**
     * Update user password.
     *
     * @return void
     */
    public function save()
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
                'message' => __("You have been successfully updated your password!")
            ]);
        } else {
            session()->flash('error', __("That is not your current password."));
        }
    }

    /**
     * Component validation rules.
     *
     * @return string[]
     */
    public function rules()
    {
        return [
            'current_password' => 'required',
            'password' => [
                'required',
                'confirmed',
                (new PasswordRules())
                    ->requireUppercase()
                    ->requireSpecialCharacter()
                    ->requireNumeric()
                ,
            ],
        ];
    }

    /**
     * Render the component.
     *
     * @return \Illuminate\View\View
     */
    public function render()
    {
        return view('shopper::livewire.account.password');
    }
}
