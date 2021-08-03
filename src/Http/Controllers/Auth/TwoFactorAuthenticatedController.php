<?php

namespace Shopper\Framework\Http\Controllers\Auth;

use Illuminate\Http\JsonResponse;
use Illuminate\Routing\Controller;
use Illuminate\Contracts\Auth\StatefulGuard;
use Illuminate\Validation\ValidationException;
use Illuminate\Http\Exceptions\HttpResponseException;
use Shopper\Framework\Http\Requests\TwoFactorLoginRequest;
use Shopper\Framework\Http\Responses\FailedTwoFactorLoginResponse;

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
     */
    public function __construct(StatefulGuard $guard)
    {
        $this->guard = $guard;
        $this->middleware('shopper.guest');
    }

    public function create(TwoFactorLoginRequest $request)
    {
        if (! $request->hasChallengedUser()) {
            throw new HttpResponseException(redirect()->route('shopper.login'));
        }

        return view('shopper::auth.two-factor-login');
    }

    /**
     * Attempt to authenticate a new session using the two factor authentication code.
     *
     * @throws ValidationException
     */
    public function store(TwoFactorLoginRequest $request)
    {
        $user = $request->challengedUser();

        if ($code = $request->validRecoveryCode()) {
            $user->replaceRecoveryCode($code);
        } elseif (! $request->hasValidCode()) {
            return app(FailedTwoFactorLoginResponse::class);
        }

        $this->guard->login($user, $request->remember());

        $request->session()->regenerate();

        return $request->wantsJson()
            ? new JsonResponse([], 204)
            : redirect(route('shopper.dashboard'));
    }
}
