<?php

declare(strict_types=1);

namespace Shopper\Sidebar\Infrastructure;

use Illuminate\Support\Str;
use Shopper\Sidebar\Exceptions\SidebarResolverNotSupported;

final class SidebarResolverFactory
{
    public static function getClassName(?string $name = null): string
    {
        if ($name) {
            $class = __NAMESPACE__ . '\\' . Str::studly($name) . 'CacheResolver';

            if (class_exists($class)) {
                return $class;
            }

            throw new SidebarResolverNotSupported('Chosen caching type is not supported. Supported: [static, user-based]');
        }

        return __NAMESPACE__ . '\\ContainerResolver';
    }
}
