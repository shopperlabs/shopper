<?php

declare(strict_types=1);

namespace Shopper\Http\Controllers\Auth;

use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Laravel\Passkeys\Actions\GenerateRegistrationOptions;
use Laravel\Passkeys\Actions\StorePasskey;
use Laravel\Passkeys\Http\Requests\PasskeyRegistrationRequest;
use Laravel\Passkeys\Support\WebAuthn;

final class PasskeyRegistrationController
{
    public function options(Request $request, GenerateRegistrationOptions $generate): JsonResponse
    {
        $this->ensurePasswordIsConfirmed($request);

        $options = $generate(shopper()->auth()->user());

        $request->session()->put('passkey.registration_options', WebAuthn::toJson($options));

        return response()->json([
            'options' => WebAuthn::toBrowserArray($options),
        ]);
    }

    public function store(PasskeyRegistrationRequest $request, StorePasskey $store): JsonResponse
    {
        $this->ensurePasswordIsConfirmed($request);

        $passkey = $store(
            shopper()->auth()->user(),
            $request->string('name')->toString(),
            $request->credential(),
            $request->registrationOptions()
        );

        return response()->json([
            'id' => $passkey->getKey(),
            'name' => $passkey->name,
        ], 201);
    }

    private function ensurePasswordIsConfirmed(Request $request): void
    {
        $confirmedAt = $request->session()->get('auth.password_confirmed_at', 0);

        abort_if(
            (time() - $confirmedAt) >= config('auth.password_timeout', 900),
            423,
            __('shopper::pages/auth.account.passkey_password_confirmation_required'),
        );
    }
}
