<?php

declare(strict_types=1);

namespace Shopper\Actions;

use Illuminate\Support\Collection;
use Shopper\Contracts\TwoFactorAuthenticationProvider;
use Shopper\Events\TwoFactor\TwoFactorAuthenticationEnabled;

final class EnableTwoFactorAuthentication
{
    public function __construct(protected TwoFactorAuthenticationProvider $provider)
    {
    }

    public function __invoke($user): void
    {
        $user->forceFill([
            'two_factor_secret' => encrypt($this->provider->generateSecretKey()),
            'two_factor_recovery_codes' => encrypt(json_encode(
                Collection::times(8, fn () => RecoveryCode::generate())->all()
            )),
        ])->save();

        TwoFactorAuthenticationEnabled::dispatch($user);
    }
}
