<?php

namespace Shopper\Framework\Providers;

use Maatwebsite\Sidebar\SidebarServiceProvider;

class ShopperSidebarServiceProvider extends SidebarServiceProvider
{
    /**
     * Register views.
     * @return void
     */
    protected function registerViews()
    {
        $this->loadViewsFrom(__DIR__ . '/../../resources/views', 'shopper');
    }
}
