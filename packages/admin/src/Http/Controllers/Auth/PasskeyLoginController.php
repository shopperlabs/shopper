<?php

declare(strict_types=1);

namespace Shopper\Http\Controllers\Auth;

use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Laravel\Passkeys\Actions\GenerateVerificationOptions;
use Laravel\Passkeys\Actions\VerifyPasskey;
use Laravel\Passkeys\Http\Requests\PasskeyVerificationRequest;
use Laravel\Passkeys\Support\WebAuthn;
use Shopper\Contracts\LoginResponse;
use Shopper\Facades\Shopper;

final class PasskeyLoginController
{
    public function options(Request $request, GenerateVerificationOptions $generate): JsonResponse
    {
        $options = $generate();

        $request->session()->put('passkey.verification_options', WebAuthn::toJson($options));

        return response()->json([
            'options' => WebAuthn::toBrowserArray($options),
        ]);
    }

    public function login(PasskeyVerificationRequest $request, VerifyPasskey $verify): JsonResponse
    {
        $passkey = $verify(
            $request->credential(),
            $request->verificationOptions()
        );

        Shopper::auth()->login($passkey->user, $request->remember());

        $request->session()->regenerate();

        $response = app(LoginResponse::class)->toResponse($request);

        return response()->json([
            'redirect' => $response instanceof RedirectResponse
                ? $response->getTargetUrl()
                : route('shopper.dashboard'),
        ]);
    }
}
