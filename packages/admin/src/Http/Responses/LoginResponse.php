<?php

declare(strict_types=1);

namespace Shopper\Http\Responses;

use Shopper\Contracts\LoginResponse as Responsable;

class LoginResponse implements Responsable
{
    public function toResponse($request)
    {
        return redirect()->intended(route('shopper.dashboard'));
    }
}
