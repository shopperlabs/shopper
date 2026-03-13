<?php

declare(strict_types=1);

namespace Shopper\Actions\Auth;

use Shopper\Contracts\HasStoreAuthentication;
use Shopper\Contracts\HasStoreAuthenticationRecovery;

class DisableTwoFactorAuthentication
{
    public function __invoke(HasStoreAuthentication $user): void
    {
        $user->saveStoreAuthenticationSecret(null);

        if ($user instanceof HasStoreAuthenticationRecovery) {
            $user->saveStoreAuthenticationRecoveryCodes(null);
        }
    }
}
