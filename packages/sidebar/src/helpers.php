<?php

declare(strict_types=1);

namespace Shopper\Sidebar;

if (! function_exists('Shopper\Sidebar\sidebar_config')) {
    function sidebar_config(?string $key = null, mixed $default = null): mixed
    {
        if ($key === null) {
            return config('sidebar');
        }

        return config("sidebar.{$key}", $default);
    }
}

if (! function_exists('Shopper\Sidebar\sidebar_width')) {
    function sidebar_width(): string
    {
        return config('sidebar.width', '16.5rem');
    }
}

if (! function_exists('Shopper\Sidebar\sidebar_collapsed_width')) {
    function sidebar_collapsed_width(): string
    {
        return config('sidebar.collapsed_width', '4.5rem');
    }
}

if (! function_exists('Shopper\Sidebar\sidebar_breakpoint')) {
    function sidebar_breakpoint(): int
    {
        return config('sidebar.breakpoint', 1024);
    }
}

if (! function_exists('Shopper\Sidebar\sidebar_is_collapsible')) {
    function sidebar_is_collapsible(): bool
    {
        return config('sidebar.collapsible', true);
    }
}
