<?php

declare(strict_types=1);

use Illuminate\Support\Str;
use Shopper\ShopperPanel;

if (! function_exists('active')) {
    function active(array $routes, string $activeClass = 'active', string $defaultClass = '', bool $condition = true): string
    {
        return call_user_func_array([app('router'), 'is'], $routes) && $condition ? $activeClass : $defaultClass;
    }
}

if (! function_exists('is_active')) {
    function is_active(array $routes): bool
    {
        return (bool) call_user_func_array([app('router'), 'is'], $routes);
    }
}

if (! function_exists('get_asset_id')) {
    function get_asset_id(string $file, ?string $manifestPath = null): ?string
    {
        $manifestPath ??= __DIR__ . '/../public/mix-manifest.json';

        if (! file_exists($manifestPath)) {
            return null;
        }

        $manifest = json_decode(file_get_contents($manifestPath), associative: true);

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
