<?php

declare(strict_types=1);

namespace Shopper\Contracts;

interface HasStoreAuthentication
{
    public function getStoreAuthenticationSecret(): ?string;

    public function saveStoreAuthenticationSecret(?string $secret): void;

    public function getStoreAuthenticationHolderName(): string;

    public function getStoreAuthenticationQrCodeSvg(): string;

    public function getStoreAuthenticationQrCodeUrl(): string;
}
