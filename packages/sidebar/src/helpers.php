<?php

declare(strict_types=1);

namespace Shopper\Sidebar;

use Shopper\Sidebar\Breadcrumbs\Breadcrumb;
use Shopper\Sidebar\Breadcrumbs\Breadcrumbs;

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
        return config('sidebar.collapsed_width', '4rem');
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

if (! function_exists('Shopper\Sidebar\sidebar_breadcrumbs')) {
    /**
     * Build the full breadcrumb trail: auto-detected from the first sidebar
     * whose menu yields auto-generated crumbs, merged with any breadcrumb
     * pushed by the current page via the `WithBreadcrumbs` trait.
     *
     * Traversing each menu happens at most once: `Breadcrumbs::build()` is
     * the single entry point, and we detect a successful match by comparing
     * the resulting crumb count to the baseline of pushed-only crumbs.
     *
     * @return list<Breadcrumb>
     */
    function sidebar_breadcrumbs(): array
    {
        $registry = resolve(Breadcrumbs::class);
        $manager = resolve(SidebarManager::class);
        $baseline = count($registry->all());

        foreach ($manager->getSidebars() as $class) {
            $crumbs = $registry->build(resolve($class)->getMenu());

            if (count($crumbs) > $baseline) {
                return $crumbs;
            }
        }

        return $registry->all();
    }
}
