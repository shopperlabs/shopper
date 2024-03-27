<?php

declare(strict_types=1);

namespace Shopper\Contracts;

interface TwoFactorAuthenticationProvider
{
    public function generateSecretKey(): string;

    public function qrCodeUrl(string $companyName, string $companyEmail, string $secret): string;

    public function verify(string $secret, string $code): bool;
}
