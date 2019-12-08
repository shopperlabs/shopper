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

        $menu->make('primaryMenu', function (Builder $item) {
            $dashboard = $item->add('<i class="flaticon2-protection"></i>', ['route' => 'shopper.dashboard', 'class' => 'aside__nav-item']);
            $dashboard->active('dashboard/*')->link->attr([
                'class' => 'aside__nav-link',
                'data-toggle' => 'tooltip',
                'data-title' => __('Dashboard'),
                'data-placement' => 'right',
                'data-container' => 'body',
                'data-boundary' => 'window'
            ]);

            $site = $item->add('<i class="flaticon2-browser-1"></i>', ['url' => url('/'), 'class' => 'aside__nav-item']);
            $site->link->attr([
                'class' => 'aside__nav-link',
                'data-toggle' => 'tooltip',
                'data-title' => __('Open site'),
                'data-placement' => 'right',
                'data-container' => 'body',
                'data-boundary' => 'window',
                'target' => '_blank'
            ]);

            $settings = $item->add('<i class="flaticon2-gear"></i>', ['route' => 'shopper.login', 'class' => 'aside__nav-item']);
            $settings->active('settings/*')->link->attr([
                'class' => 'aside__nav-link',
                'data-toggle' => 'tooltip',
                'data-title' => __('System settings'),
                'data-placement' => 'right',
                'data-container' => 'body',
                'data-boundary' => 'window'
            ]);

            $shop = $item->add('<i class="flaticon2-shopping-cart"></i>', ['route' => 'shopper.login', 'class' => 'aside__nav-item']);
            $shop->active('shop/*')->link->attr([
                'class' => 'aside__nav-link',
                'data-toggle' => 'tooltip',
                'data-title' => __('Shop Settings'),
                'data-placement' => 'right',
                'data-container' => 'body',
                'data-boundary' => 'window'
            ]);

            $access = $item->add('<i class="flaticon2-lock"></i>', ['route' => 'shopper.login', 'class' => 'aside__nav-item']);
            $access->active('access/*')->link->attr([
                'class' => 'aside__nav-link',
                'data-toggle' => 'tooltip',
                'data-title' => __('Access'),
                'data-placement' => 'right',
                'data-container' => 'body',
                'data-boundary' => 'window'
            ]);

            $logger = $item->add('<i class="flaticon2-list-3"></i>', ['route' => 'shopper.login', 'class' => 'aside__nav-item']);
            $logger->active('logger/*')->link->attr([
                'class' => 'aside__nav-link',
                'data-toggle' => 'tooltip',
                'data-title' => __('Logs reports'),
                'data-placement' => 'right',
                'data-container' => 'body',
                'data-boundary' => 'window'
            ]);
        });

        $view->with('menu', $menu);
    }
}
