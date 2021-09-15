<?php

namespace Shopper\Framework\Actions;

class DisableTwoFactorAuthentication
{
    /**
     * Disable two factor authentication for the user.
     */
    public function __invoke($user)
    {
        $user->forceFill([
            'two_factor_secret' => null,
            'two_factor_recovery_codes' => null,
        ])->save();
    }
}
