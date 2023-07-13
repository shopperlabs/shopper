<?php

declare(strict_types=1);

namespace Shopper\Events\TwoFactor;

use Illuminate\Foundation\Events\Dispatchable;

abstract class TwoFactorAuthenticationEvent
{
    use Dispatchable;

    public function __construct(public $user)
    {
    }
}
