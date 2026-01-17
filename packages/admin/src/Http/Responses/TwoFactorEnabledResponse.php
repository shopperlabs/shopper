<?php

declare(strict_types=1);

namespace Shopper\Http\Responses;

use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Shopper\Contracts\TwoFactorLoginResponse as TwoFactorLoginResponseContract;
use Symfony\Component\HttpFoundation\Response;

class TwoFactorEnabledResponse implements TwoFactorLoginResponseContract
{
    /**
     * @param  Request  $request
     * @return JsonResponse|RedirectResponse|Response
     */
    public function toResponse($request)
    {
        return $request->wantsJson()
            ? new JsonResponse('', 200)
            : back()->with('status', 'two-factor-authentication-enabled');
    }
}
