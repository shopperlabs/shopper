<?php

declare(strict_types=1);

namespace Shopper\Http\Controllers\Auth;

use Illuminate\Contracts\View\View;
use Illuminate\Http\Exceptions\HttpResponseException;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Routing\Controller;
use Shopper\Facades\Shopper;
use Shopper\Http\Middleware\RedirectIfAuthenticated;
use Shopper\Http\Requests\TwoFactorLoginRequest;
use Shopper\Http\Responses\FailedTwoFactorLoginResponse;

final class TwoFactorAuthenticatedController extends Controller
{
    public function __construct()
    {
        $this->middleware(RedirectIfAuthenticated::class);
    }

    public function create(TwoFactorLoginRequest $request): View
    {
        if (! $request->hasChallengedUser()) {
            throw new HttpResponseException(redirect()->route('shopper.login'));
        }

        return view('shopper::auth.two-factor-login');
    }

    public function store(TwoFactorLoginRequest $request): FailedTwoFactorLoginResponse | JsonResponse | RedirectResponse
    {
        $user = $request->challengedUser();

        if ($code = $request->validRecoveryCode()) {
            $user->replaceRecoveryCode($code);
        } elseif (! $request->hasValidCode()) {
            return app(FailedTwoFactorLoginResponse::class);
        }

        Shopper::auth()->login($user, $request->remember());

        $request->session()->regenerate();

        return $request->wantsJson()
            ? new JsonResponse([], 204)
            : redirect(route('shopper.dashboard'));
    }
}
