<?php

namespace Shopper\Framework\Http\Controllers\Auth;

use Illuminate\Foundation\Auth\ResetsPasswords;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Illuminate\Validation\Rules\Password;
use Shopper\Framework\Rules\RealEmailValidator;
use Shopper\Framework\Shopper;

class ResetPasswordController extends Controller
{
    use ValidatesRequests;

    /*
    |--------------------------------------------------------------------------
    | Password Reset Controller
    |--------------------------------------------------------------------------
    |
    | This controller is responsible for handling password reset requests
    | and uses a simple trait to include this behavior. You're free to
    | explore this trait and override any methods you wish to tweak.
    |
    */

    use ResetsPasswords;

    public function __construct()
    {
        $this->middleware('guest');
    }

    public function showResetForm(Request $request, ?string $token = null)
    {
        return view('shopper::auth.passwords.reset-password')->with(
            ['token' => $token, 'email' => $request->email]
        );
    }

    /**
     * Get the URI the user should be redirected to after resetting their password.
     *
     * @return string
     */
    public function redirectPath()
    {
        return Shopper::prefix();
    }

    /**
     * Get the password reset validation rules.
     *
     * @return array
     */
    protected function rules()
    {
        return [
            'token' => 'required',
            'email' => ['required', 'email', new RealEmailValidator()],
            'password' => [
                'required',
                Password::min(8)->numbers()->symbols()->mixedCase(),
            ],
        ];
    }
}
