<?php

declare(strict_types=1);

namespace Shopper\Contracts;

interface HasStoreAuthenticationRecovery
{
    /** @return ?array<string> */
    public function getStoreAuthenticationRecoveryCodes(): ?array;

    /** @param ?array<string> $codes */
    public function saveStoreAuthenticationRecoveryCodes(?array $codes): void;

    public function replaceStoreAuthenticationRecoveryCode(string $code): void;
}
