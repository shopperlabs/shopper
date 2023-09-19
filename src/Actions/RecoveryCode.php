<?php

declare(strict_types=1);

namespace Shopper\Framework\Actions;

use Illuminate\Support\Str;

final class RecoveryCode
{
    public static function generate(): string
    {
        return Str::random(10) . '-' . Str::random(10);
    }
}
