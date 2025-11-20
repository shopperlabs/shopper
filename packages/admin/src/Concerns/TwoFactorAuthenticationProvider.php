<?php

declare(strict_types=1);

namespace Shopper\Concerns;

use PragmaRX\Google2FA\Exceptions\IncompatibleWithGoogleAuthenticatorException;
use PragmaRX\Google2FA\Exceptions\InvalidCharactersException;
use PragmaRX\Google2FA\Exceptions\SecretKeyTooShortException;
use PragmaRX\Google2FA\Google2FA;
use Shopper\Contracts\TwoFactorAuthenticationProvider as TwoFactorAuthenticationProviderContract;

final class TwoFactorAuthenticationProvider implements TwoFactorAuthenticationProviderContract
{
    public function __construct(private readonly Google2FA $engine) {}

    /**
     * @throws IncompatibleWithGoogleAuthenticatorException
     * @throws InvalidCharactersException
     * @throws SecretKeyTooShortException
     */
    public function generateSecretKey(): string
    {
        return $this->engine->generateSecretKey();
    }

    public function qrCodeUrl(string $companyName, string $companyEmail, string $secret): string
    {
        return $this->engine->getQRCodeUrl($companyName, $companyEmail, $secret);
    }

    /**
     * @throws IncompatibleWithGoogleAuthenticatorException
     * @throws SecretKeyTooShortException
     * @throws InvalidCharactersException
     */
    public function verify(string $secret, string $code): bool|int
    {
        return $this->engine->verifyKey($secret, $code);
    }
}
