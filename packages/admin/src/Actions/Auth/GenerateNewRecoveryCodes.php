<?php

declare(strict_types=1);

namespace Shopper\Actions\Auth;

use Illuminate\Support\Collection;
use Shopper\Core\Contracts\ShopperUser;

class GenerateNewRecoveryCodes
{
    public function __invoke(ShopperUser $user): void
    {
        $user->forceFill([
            'two_factor_recovery_codes' => encrypt(json_encode(
                Collection::times(8, fn (): string => RecoveryCode::generate())->all()
            )),
        ])->save();
    }
}
