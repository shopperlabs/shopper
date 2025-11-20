<?php

declare(strict_types=1);

use Illuminate\Support\Str;
use Shopper\ShopperPanel;

if (! function_exists('get_asset_id')) {
    function get_asset_id(string $file, ?string $manifestPath = null): ?string
    {
        $manifestPath ??= __DIR__.'/../public/mix-manifest.json';

        if (! file_exists($manifestPath)) {
            return null;
        }

        /** @var array<string, mixed> $manifest */
        $manifest = json_decode((string) file_get_contents($manifestPath), associative: true);

        $file = "/{$file}";

        if (! array_key_exists($file, $manifest)) {
            return null;
        }

        $file = $manifest[$file];

        if (! str_contains($file, 'id=')) {
            return null;
        }

        return (string) Str::of($file)->after('id=');
    }
}

if (! function_exists('shopper')) {
    function shopper(): ShopperPanel
    {
        /** @var ShopperPanel $shopper */
        $shopper = app('shopper');

        return $shopper;
    }
}

if (! function_exists('shopper_fallback_url')) {
    function shopper_fallback_url(): string
    {
        return shopper_panel_assets('/images/placeholder.jpg');
    }
}

if (! function_exists('shopper_panel_asset')) {
    function shopper_panel_assets(string $asset): string
    {
        return url(shopper()->prefix().$asset);
    }
}
