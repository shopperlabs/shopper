<?php

declare(strict_types=1);

namespace Shopper\Framework\Contracts;

interface TwoFactorAuthenticationProvider
{
    /**
     * Generate a new secret key.
     */
    public function generateSecretKey(): string;

    /**
     * Get the two factor authentication QR code URL.
     */
    public function qrCodeUrl(string $companyName, string $companyEmail, string $secret): string;

    /**
     * Verify the given token.
     */
    public function verify(string $secret, string $code): bool;
}
