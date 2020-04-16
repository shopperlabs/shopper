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
        });

        $view->with('menu', $menu);
    }
}
