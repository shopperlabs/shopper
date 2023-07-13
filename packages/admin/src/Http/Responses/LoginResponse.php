<?php

declare(strict_types=1);

namespace Shopper\Http\Responses;

use Shopper\Contracts\LoginResponse as Responsable;
use Symfony\Component\HttpFoundation\Response;

final class LoginResponse implements Responsable
{
    public function toResponse($request): Response
    {
        return redirect()->intended(route('shopper.dashboard'));
    }
}
