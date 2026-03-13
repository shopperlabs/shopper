<?php

declare(strict_types=1);

namespace Shopper\Traits;

use Shopper\Actions\Auth\RecoveryCode;

/**
 * @property ?string $store_two_factor_recovery_codes
 */
trait InteractsWithStoreAuthenticationRecovery
{
    /** @return ?array<string> */
    public function getStoreAuthenticationRecoveryCodes(): ?array
    {
        if ($this->store_two_factor_recovery_codes === null) {
            return null;
        }

        return json_decode(decrypt($this->store_two_factor_recovery_codes), true);
    }

    /** @param ?array<string> $codes */
    public function saveStoreAuthenticationRecoveryCodes(?array $codes): void
    {
        $this->forceFill([
            'store_two_factor_recovery_codes' => $codes !== null ? encrypt(json_encode($codes)) : null,
        ])->save();
    }

    public function replaceStoreAuthenticationRecoveryCode(string $code): void
    {
        $this->forceFill([
            'store_two_factor_recovery_codes' => encrypt(str_replace(
                $code,
                RecoveryCode::generate(),
                decrypt($this->store_two_factor_recovery_codes)
            )),
        ])->save();
    }
}
