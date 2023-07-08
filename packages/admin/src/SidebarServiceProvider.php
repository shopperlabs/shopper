<?php

declare(strict_types=1);

namespace Shopper;

use Illuminate\Support\ServiceProvider;
use Shopper\Events\DashboardSidebar;
use Shopper\Events\OrderSidebar;
use Shopper\Events\ShopSidebar;
use Shopper\Sidebar\SidebarBuilder;
use Shopper\Sidebar\SidebarCreator;
use Shopper\Sidebar\SidebarManager;
use Shopper\Sidebar\AdminSidebar;

final class SidebarServiceProvider extends ServiceProvider
{
    public function boot(SidebarManager $manager): void
    {
        $manager->register(AdminSidebar::class);
    }

    public function register(): void
    {
        $this->app['events']->listen(SidebarBuilder::class, DashboardSidebar::class);
        $this->app['events']->listen(SidebarBuilder::class, ShopSidebar::class);
        $this->app['events']->listen(SidebarBuilder::class, OrderSidebar::class);

        view()->creator('shopper::components.layouts.app.sidebar.secondary', SidebarCreator::class);
    }
}
