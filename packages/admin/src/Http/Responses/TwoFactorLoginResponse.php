<?php

declare(strict_types=1);

namespace Shopper\Http\Responses;

use Illuminate\Http\JsonResponse;
use Shopper\Contracts\TwoFactorLoginResponse as TwoFactorLoginResponseContract;
use Symfony\Component\HttpFoundation\Response;

final class TwoFactorLoginResponse implements TwoFactorLoginResponseContract
{
    public function toResponse($request): Response
    {
        return $request->wantsJson()
            ? new JsonResponse('', 204)
            : redirect()->intended(route('shopper.dashboard'));
    }
}
