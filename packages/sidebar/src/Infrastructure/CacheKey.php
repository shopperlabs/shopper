<?php

declare(strict_types=1);

namespace Shopper\Sidebar\Infrastructure;

final class CacheKey
{
    public static function get(string $name, int|string|null $id = null): string
    {
        if ($id) {
            $name .= '-'.$id;
        }

        return 'shopper.sidebar.'.md5($name);
    }
}
