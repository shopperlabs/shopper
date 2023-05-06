<?php

declare(strict_types=1);

namespace Shopper\Framework\Http\Controllers\Auth;

use Illuminate\Contracts\Auth\StatefulGuard;
use Illuminate\Contracts\View\View;
use Illuminate\Http\Exceptions\HttpResponseException;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Routing\Controller;
use Illuminate\Validation\ValidationException;
use Shopper\Framework\Http\Requests\TwoFactorLoginRequest;
use Shopper\Framework\Http\Responses\FailedTwoFactorLoginResponse;

class TwoFactorAuthenticatedController extends Controller
{
    public function __construct(protected StatefulGuard $guard)
    {
        $this->middleware('shopper.guest');
    }

    public function create(TwoFactorLoginRequest $request): View
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
    public function store(TwoFactorLoginRequest $request): JsonResponse|RedirectResponse
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
