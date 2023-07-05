<?php

namespace Shopper\Sidebar\Infrastructure;

final class CacheKey
{
    public static function get(string $name, int $id = null): string
    {
        if ($id) {
            $name .= '-' . $id;
        }

        return md5($name);
    }
}
