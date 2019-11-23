<?php

namespace Shopper\Framework\Providers;

use Illuminate\Support\ServiceProvider;
use Maatwebsite\Sidebar\SidebarManager;
use Shopper\Framework\Sidebar\AdminSidebar;

class SidebarServiceProvider extends ServiceProvider
{
    /**
     * Sidebar Manager
     *
     * @param SidebarManager $manager
     */
    public function boot(SidebarManager $manager)
    {
        $manager->register(AdminSidebar::class);
    }
}
