<?php

declare(strict_types=1);

namespace Shopper\Actions\Auth;

use Illuminate\Support\Collection;
use Shopper\Contracts\HasStoreAuthenticationRecovery;

class GenerateNewRecoveryCodes
{
    public function __invoke(HasStoreAuthenticationRecovery $user): void
    {
        $user->saveStoreAuthenticationRecoveryCodes(
            Collection::times(8, fn (): string => RecoveryCode::generate())->all()
        );
    }
}
