<?php

namespace Shopper\Framework\Http\Composers;

use Illuminate\View\View;
use Lavary\Menu\Builder;
use Lavary\Menu\Menu;

class MenuCreator
{
    /**
     * Bind Menu to the view.
     *
     * @param View $view
     */
    public function compose(View $view)
    {
        $menu = new Menu();
        $class = 'group block p-2 text-base leading-6 font-medium rounded-md text-on-primary hover:text-on-primary hover:bg-primary-light focus:outline-none focus:text-on-primary focus:bg-primary-light transition ease-in-out duration-150';
        $svgClass = 'h-6 w-6 text-on-primary group-hover:text-on-primary group-focus:text-on-primary transition ease-in-out duration-150';

        $menu->make('primaryMenu', function (Builder $item) use ($class, $svgClass) {
            $dashboardIcon = '<svg class='. $svgClass .' stroke="currentColor" fill="none" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l9-9 9 9M5 10v10a1 1 0 001 1h3a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1h3a1 1 0 001-1V10M9 21h6" />
            </svg>';
            $dashboard = $item->add($dashboardIcon, ['route' => 'shopper.dashboard', 'class' => $class]);
            $dashboard->active('dashboard/*')->link->attr([
                'class' => '',
                'data-toggle' => 'tooltip',
                'data-title' => __('Dashboard'),
                'title' => __('Dashboard'),
                'data-placement' => 'right',
                'data-container' => 'body',
                'data-boundary' => 'window'
            ]);

            $siteIcon = '<svg class='. $svgClass .' fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 6H6a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-4M14 4h6m0 0v6m0-6L10 14" />
             </svg>';
            $site = $item->add($siteIcon, ['url' => url('/'), 'class' => $class]);
            $site->link->attr([
                'class' => '',
                'data-toggle' => 'tooltip',
                'data-title' => __('Open site'),
                'title' => __('Open site'),
                'data-placement' => 'right',
                'data-container' => 'body',
                'data-boundary' => 'window',
                'target' => '_blank'
            ]);

            $settingsIcon = '<svg class='. $svgClass .' fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z" />
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
             </svg>';
            $settings = $item->add($settingsIcon, ['route' => 'shopper.login', 'class' => $class]);
            $settings->active('settings/*')->link->attr([
                'class' => '',
                'data-toggle' => 'tooltip',
                'data-title' => __('System settings'),
                'title' => __('System settings'),
                'data-placement' => 'right',
                'data-container' => 'body',
                'data-boundary' => 'window'
            ]);

            $shopIcon = '<svg class='. $svgClass .' fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4" />
             </svg>';
            $shop = $item->add($shopIcon, ['route' => 'shopper.shop.setting', 'class' => $class]);
            $shop->active('shop/setting')->link->attr([
                'class' => '',
                'data-toggle' => 'tooltip',
                'data-title' => __('My Shop'),
                'title' => __('My Shop'),
                'data-placement' => 'right',
                'data-container' => 'body',
                'data-boundary' => 'window'
            ]);

            $accessIcon = '<svg class='. $svgClass .' fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z" />
             </svg>';
            $access = $item->add($accessIcon, ['route' => 'shopper.users.access', 'class' => $class]);
            $access->active('access/*')->link->attr([
                'class' => '',
                'data-toggle' => 'tooltip',
                'data-title' => __('Access'),
                'title' => __('Access'),
                'data-placement' => 'right',
                'data-container' => 'body',
                'data-boundary' => 'window'
            ]);

            $loggerIcon = '<svg class='. $svgClass .' fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 8v8m-4-5v5m-4-2v2m-2 4h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z" />
             </svg>';
            $logger = $item->add($loggerIcon, ['route' => 'shopper.login', 'class' => $class]);
            $logger->active('logger/*')->link->attr([
                'class' => '',
                'data-toggle' => 'tooltip',
                'data-title' => __('Logs reports'),
                'title' => __('Logs reports'),
                'data-placement' => 'right',
                'data-container' => 'body',
                'data-boundary' => 'window'
            ]);
        });

        $view->with('menu', $menu);
    }
}
