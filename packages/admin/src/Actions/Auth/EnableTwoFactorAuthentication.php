<?php

declare(strict_types=1);

namespace Shopper\Actions\Auth;

use Illuminate\Support\Collection;
use Shopper\Contracts\HasStoreAuthentication;
use Shopper\Contracts\HasStoreAuthenticationRecovery;
use Shopper\Contracts\TwoFactorAuthenticationProvider;
use Shopper\Events\TwoFactor\TwoFactorAuthenticationEnabled;

class EnableTwoFactorAuthentication
{
    public function __construct(protected TwoFactorAuthenticationProvider $provider) {}

    public function __invoke(HasStoreAuthentication $user): void
    {
        $user->saveStoreAuthenticationSecret($this->provider->generateSecretKey());

        if ($user instanceof HasStoreAuthenticationRecovery) {
            $user->saveStoreAuthenticationRecoveryCodes(
                Collection::times(8, fn (): string => RecoveryCode::generate())->all()
            );
        }

        TwoFactorAuthenticationEnabled::dispatch($user);
    }
}
