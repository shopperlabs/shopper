<?php

namespace Shopper\Framework\Actions;

use Illuminate\Support\Collection;

class GenerateNewRecoveryCodes
{
    /**
     * Disable two factor authentication for the user.
     */
    public function __invoke($user)
    {
        $user->forceFill([
            'two_factor_recovery_codes' => encrypt(json_encode(Collection::times(8, fn () => RecoveryCode::generate())->all())),
        ])->save();
    }
}
