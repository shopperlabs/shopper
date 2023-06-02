<?php

declare(strict_types=1);

namespace Shopper\Core;

final class Shopper
{
    public static function version(): string
    {
        return '2.0';
    }

    public static function prefix(): string
    {
        return config('shopper.admin.prefix');
    }

    public static function username(): string
    {
        return config('shopper.auth.username', 'email');
    }
}
