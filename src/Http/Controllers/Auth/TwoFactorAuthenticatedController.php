<?php

namespace Shopper\Framework\Http\Controllers\Auth;

use Illuminate\Contracts\Auth\StatefulGuard;
use Illuminate\Http\JsonResponse;
use Illuminate\Routing\Controller;
use Illuminate\Validation\ValidationException;
use Shopper\Framework\Http\Requests\TwoFactorLoginRequest;

class TwoFactorAuthenticatedController extends Controller
{
    /**
     * The guard implementation.
     *
     * @var \Illuminate\Contracts\Auth\StatefulGuard
     */
    protected $guard;

    /**
     * Create a new controller instance.
     *
     * @param  \Illuminate\Contracts\Auth\StatefulGuard
     * @return void
     */
    public function __construct(StatefulGuard $guard)
    {
        $this->guard = $guard;
        $this->middleware('shopper.guest');
    }

    /**
     * Show the two factor authentication login view.
     *
     * @return \Illuminate\View\View
     */
    public function create()
    {
        return view('shopper::auth.two-factor-login');
    }

    /**
     * Attempt to authenticate a new session using the two factor authentication code.
     *
     * @param \Shopper\Framework\Http\Requests\TwoFactorLoginRequest $request
     * @return  mixed
     * @throws ValidationException
     */
    public function store(TwoFactorLoginRequest $request)
    {
        $user = $request->challengedUser();

        if ($code = $request->validRecoveryCode()) {
            $user->replaceRecoveryCode($code);
        } elseif (! $request->hasValidCode()) {
            $message = __('The provided two factor authentication code was invalid.');

            if ($request->wantsJson()) {
                throw ValidationException::withMessages([
                    'code' => [$message],
                ]);
            }

            return redirect()->route('shopper.login')->withErrors(['email' => $message]);
        }

        $this->guard->login($user, $request->remember());

        return $request->wantsJson()
            ? new JsonResponse([], 204)
            : redirect(route('shopper.dashboard'));
    }
}
