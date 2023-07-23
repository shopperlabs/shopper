<?php

declare(strict_types=1);

namespace Shopper\Http\Responses;

use Illuminate\Http\RedirectResponse;
use Livewire\Redirector;
use Shopper\Contracts\LoginResponse as Responsable;

final class LoginResponse implements Responsable
{
    public function toResponse($request): Redirector | RedirectResponse
    {
        return redirect()->intended(route('shopper.dashboard'));
    }
}
