<?php

namespace Shopper\Framework\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Contracts\Foundation\Application;
use Shopper\Framework\Sidebar\Domain\DefaultItem;
use Maatwebsite\Sidebar\Infrastructure\SidebarFlusherFactory;
use Maatwebsite\Sidebar\Infrastructure\SidebarResolverFactory;
use Shopper\Framework\Sidebar\Presentation\ShopperSidebarRenderer;

class ShopperSidebarServiceProvider extends ServiceProvider
{
    /**
     * Boot the service provider.
     */
    public function boot()
    {
        $this->registerViews();
    }

    /**
     * Register the service provider.
     */
    public function register()
    {
        // Bind SidebarResolver
        $this->app->bind('Maatwebsite\Sidebar\Infrastructure\SidebarResolver', function (Application $app) {
            $resolver = SidebarResolverFactory::getClassName(
                $app['config']->get('shopper.config.cache.method')
            );

            return $app->make($resolver);
        });

        // Bind SidebarFlusher
        $this->app->bind('Maatwebsite\Sidebar\Infrastructure\SidebarFlusher', function (Application $app) {
            $resolver = SidebarFlusherFactory::getClassName(
                $app['config']->get('shopper.config.cache.method')
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

    /**
     * Get the services provided by the provider.
     */
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

    /**
     * Register views.
     */
    protected function registerViews()
    {
        $this->loadViewsFrom(SHOPPER_PATH . '/resources/views', 'shopper');
    }
}
