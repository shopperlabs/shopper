<?php

declare(strict_types=1);

namespace Shopper\Actions\Auth;

use Shopper\Models\Contracts\ShopperUser;

class DisableTwoFactorAuthentication
{
    public function __invoke(ShopperUser $user): void
    {
        $user->forceFill([
            'store_two_factor_secret' => null,
            'store_two_factor_recovery_codes' => null,
        ])->save();
    }
}
