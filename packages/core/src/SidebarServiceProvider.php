<?php

declare(strict_types=1);

namespace Shopper\Core;

use Illuminate\Contracts\Foundation\Application;
use Illuminate\Support\ServiceProvider;
use Maatwebsite\Sidebar\Infrastructure\SidebarFlusherFactory;
use Maatwebsite\Sidebar\Infrastructure\SidebarResolverFactory;
use Maatwebsite\Sidebar\SidebarManager;
use Shopper\Core\Sidebar\AdminSidebar;
use Shopper\Core\Sidebar\Domain\DefaultItem;
use Shopper\Core\Sidebar\Presentation\ShopperSidebarRenderer;

final class SidebarServiceProvider extends ServiceProvider
{
    public function boot(SidebarManager $manager): void
    {
        $manager->register(AdminSidebar::class);
    }

    public function register(): void
    {
        // Bind SidebarResolver
        $this->app->bind('Maatwebsite\Sidebar\Infrastructure\SidebarResolver', function (Application $app) {
            $resolver = SidebarResolverFactory::getClassName(
                $app['config']->get('shopper.core.cache.method')
            );

            return $app->make($resolver);
        });

        // Bind SidebarFlusher
        $this->app->bind('Maatwebsite\Sidebar\Infrastructure\SidebarFlusher', function (Application $app) {
            $resolver = SidebarFlusherFactory::getClassName(
                $app['config']->get('shopper.system.cache.method')
            );

            return $app->make($resolver);
        });

        // Bind manager
        $this->app->singleton('Maatwebsite\Sidebar\SidebarManager');

        // Bind Menu
        $this->app->bind(
            'Maatwebsite\Sidebar\Menu',
            'Maatwebsite\Sidebar\Domain\DefaultMenu'
        );

        // Bind Group
        $this->app->bind(
            'Maatwebsite\Sidebar\Group',
            'Maatwebsite\Sidebar\Domain\DefaultGroup'
        );

        // Bind Item
        $this->app->bind(
            'Maatwebsite\Sidebar\Item',
            DefaultItem::class
        );

        // Bind Badge
        $this->app->bind(
            'Maatwebsite\Sidebar\Badge',
            'Maatwebsite\Sidebar\Domain\DefaultBadge'
        );

        // Bind Append
        $this->app->bind(
            'Maatwebsite\Sidebar\Append',
            'Maatwebsite\Sidebar\Domain\DefaultAppend'
        );

        // Bind Renderer
        $this->app->bind(
            'Maatwebsite\Sidebar\Presentation\SidebarRenderer',
            ShopperSidebarRenderer::class
        );
    }

    public function provides(): array
    {
        return [
            'Maatwebsite\Sidebar\Menu',
            'Maatwebsite\Sidebar\Item',
            'Maatwebsite\Sidebar\Group',
            'Maatwebsite\Sidebar\Badge',
            'Maatwebsite\Sidebar\Append',
            'Maatwebsite\Sidebar\SidebarManager',
            'Maatwebsite\Sidebar\Presentation\SidebarRenderer',
            'Maatwebsite\Sidebar\Infrastructure\SidebarResolver',
        ];
    }
}
