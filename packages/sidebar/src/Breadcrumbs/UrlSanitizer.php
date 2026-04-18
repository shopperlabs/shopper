<?php

declare(strict_types=1);

namespace Shopper\Sidebar\Breadcrumbs;

final class UrlSanitizer
{
    private const array SAFE_PREFIXES = ['/', '#', '?', 'http://', 'https://'];

    public static function sanitize(?string $url): ?string
    {
        if ($url === null) {
            return null;
        }

        $trimmed = mb_ltrim($url);

        if ($trimmed === '') {
            return null;
        }

        return self::hasSafePrefix($trimmed) ? $url : null;
    }

    private static function hasSafePrefix(string $trimmed): bool
    {
        $lower = mb_strtolower($trimmed);

        foreach (self::SAFE_PREFIXES as $prefix) {
            if (str_starts_with($lower, $prefix)) {
                return true;
            }
        }

        return false;
    }
}
