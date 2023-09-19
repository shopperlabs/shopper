<?php

declare(strict_types=1);

namespace Shopper\Framework\Actions;

use Illuminate\Support\Collection;
use Shopper\Framework\Contracts\TwoFactorAuthenticationProvider;

final class EnableTwoFactorAuthentication
{
    public function __construct(protected TwoFactorAuthenticationProvider $provider)
    {
    }

    public function __invoke($user): void
    {
        $user->forceFill([
            'two_factor_secret' => encrypt($this->provider->generateSecretKey()),
            'two_factor_recovery_codes' => encrypt(json_encode(Collection::times(8, fn () => RecoveryCode::generate())->all())),
        ])->save();
    }
}
