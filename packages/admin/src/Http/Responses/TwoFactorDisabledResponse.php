<?php

declare(strict_types=1);

namespace Shopper\Http\Responses;

use Illuminate\Http\JsonResponse;
use Shopper\Contracts\TwoFactorLoginResponse as TwoFactorLoginResponseContract;
use Symfony\Component\HttpFoundation\Response;

final class TwoFactorDisabledResponse implements TwoFactorLoginResponseContract
{
    public function toResponse($request): Response
    {
        return $request->wantsJson()
            ? new JsonResponse('', 200)
            : back()->with('status', 'two-factor-authentication-disabled');
    }
}
