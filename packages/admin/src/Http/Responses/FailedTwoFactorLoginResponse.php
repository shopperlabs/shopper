<?php

declare(strict_types=1);

namespace Shopper\Http\Responses;

use Illuminate\Validation\ValidationException;
use Shopper\Contracts\FailedTwoFactorLoginResponse as FailedTwoFactorLoginResponseContract;
use Symfony\Component\HttpFoundation\Response;

class FailedTwoFactorLoginResponse implements FailedTwoFactorLoginResponseContract
{
    /**
     * @param  \Illuminate\Http\Request  $request
     */
    public function toResponse($request): Response
    {
        [$key, $message] = $request->filled('recovery_code')
            ? ['recovery_code', __('The provided two factor recovery code was invalid.')]
            : ['code', __('The provided two factor authentication code was invalid.')];

        if ($request->wantsJson()) {
            throw ValidationException::withMessages([$key => $message]);
        }

        session()->flash('error', $message);

        return back()->withErrors([$key => $message]);
    }
}
