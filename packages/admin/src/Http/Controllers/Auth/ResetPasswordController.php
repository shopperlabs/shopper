<?php

declare(strict_types=1);

namespace Shopper\Framework\Http\Controllers\Auth;

use Illuminate\Contracts\View\View;
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
    use ResetsPasswords;

    public function __construct()
    {
        $this->middleware('guest');
    }

    public function showResetForm(Request $request, ?string $token = null): View
    {
        return view('shopper::auth.passwords.reset-password')->with(
            ['token' => $token, 'email' => $request->email]
        );
    }

    public function redirectPath(): string
    {
        return Shopper::prefix();
    }

    protected function rules(): array
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
