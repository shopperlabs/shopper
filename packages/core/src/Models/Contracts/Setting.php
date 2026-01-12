<?php

declare(strict_types=1);

namespace Shopper\Core\Models\Contracts;

interface Setting
{
    public static function lockedAttributesDisplayName(string $key): string;
}
