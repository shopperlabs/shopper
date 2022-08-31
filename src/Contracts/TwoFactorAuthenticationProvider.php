<?php

namespace Shopper\Framework\Contracts;

interface TwoFactorAuthenticationProvider
{
    /**
     * Generate a new secret key.
     *
     * @return string
     */
    public function generateSecretKey(): string;

    /**
     * Get the two factor authentication QR code URL.
     *
     * @param  string  $companyName
     * @param  string  $companyEmail
     * @param  string  $secret
     * @return string
     */
    public function qrCodeUrl(string $companyName, string $companyEmail, string $secret): string;

    /**
     * Verify the given token.
     *
     * @param  string  $secret
     * @param  string  $code
     * @return bool
     */
    public function verify(string $secret, string $code): bool;
}
