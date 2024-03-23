<?php

declare(strict_types=1);

namespace Shopper\Providers;

use Illuminate\Support\ServiceProvider;
use Shopper\Events\CatalogSidebar;
use Shopper\Events\CustomerSidebar;
use Shopper\Events\DashboardSidebar;
use Shopper\Events\SalesSidebar;
use Shopper\Sidebar\AdminSidebar;
use Shopper\Sidebar\SidebarBuilder;
use Shopper\Sidebar\SidebarCreator;
use Shopper\Sidebar\SidebarManager;

final class SidebarServiceProvider extends ServiceProvider
{
    public function boot(SidebarManager $manager): void
    {
        $manager->register(AdminSidebar::class);
    }

    public function register(): void
    {
        $this->app['events']->listen(SidebarBuilder::class, DashboardSidebar::class);
        $this->app['events']->listen(SidebarBuilder::class, CatalogSidebar::class);
        $this->app['events']->listen(SidebarBuilder::class, SalesSidebar::class);
        $this->app['events']->listen(SidebarBuilder::class, CustomerSidebar::class);

        view()->creator('shopper::components.layouts.app.sidebar.secondary', SidebarCreator::class);
    }
}
