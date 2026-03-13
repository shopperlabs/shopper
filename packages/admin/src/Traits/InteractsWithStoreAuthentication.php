<?php

declare(strict_types=1);

namespace Shopper\Traits;

use BaconQrCode\Renderer\Color\Rgb;
use BaconQrCode\Renderer\Image\SvgImageBackEnd;
use BaconQrCode\Renderer\ImageRenderer;
use BaconQrCode\Renderer\RendererStyle\Fill;
use BaconQrCode\Renderer\RendererStyle\RendererStyle;
use BaconQrCode\Writer;
use Shopper\Contracts\TwoFactorAuthenticationProvider;

/**
 * @property ?string $store_two_factor_secret
 */
trait InteractsWithStoreAuthentication
{
    public function getStoreAuthenticationSecret(): ?string
    {
        return $this->store_two_factor_secret;
    }

    public function saveStoreAuthenticationSecret(?string $secret): void
    {
        $this->forceFill([
            'store_two_factor_secret' => $secret !== null ? encrypt($secret) : null,
        ])->save();
    }

    public function getStoreAuthenticationHolderName(): string
    {
        return $this->email;
    }

    public function getStoreAuthenticationQrCodeSvg(): string
    {
        $svg = (new Writer(
            new ImageRenderer(
                new RendererStyle(192, 0, null, null, Fill::uniformColor(new Rgb(255, 255, 255), new Rgb(45, 55, 72))),
                new SvgImageBackEnd
            )
        ))->writeString($this->getStoreAuthenticationQrCodeUrl());

        return mb_trim(mb_substr($svg, mb_strpos($svg, "\n") + 1));
    }

    public function getStoreAuthenticationQrCodeUrl(): string
    {
        return app(TwoFactorAuthenticationProvider::class)->qrCodeUrl(
            config('app.name'),
            $this->getStoreAuthenticationHolderName(),
            decrypt($this->store_two_factor_secret)
        );
    }
}
