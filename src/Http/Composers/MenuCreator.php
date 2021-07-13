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
        $user = auth()->user();
        $menu = new Menu();
        $class = 'block p-2 text-base leading-6 font-medium rounded-md text-white hover:bg-blue-700 focus:outline-none focus:text-white focus:bg-blue-900 transition ease-in-out duration-200';
        $svgClass = 'h-6 w-6 text-white transition ease-in-out duration-150';

        $menu->make('primaryMenu', function (Builder $item) use ($class, $svgClass, $user) {
            $dashboardIcon = '<svg class=' . $svgClass . ' stroke="currentColor" fill="none" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 8v8m-4-5v5m-4-2v2m-2 4h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z" />
            </svg>';
            $dashboard = $item->add($dashboardIcon, ['route' => 'shopper.dashboard', 'class' => $class]);
            $dashboard->active('dashboard/*')->link->attr([
                'class' => '',
                'data-toggle' => 'tooltip',
                'data-title' => __('Dashboard'),
                'title' => __('Dashboard'),
            ]);

            $siteIcon = '<svg class=' . $svgClass . ' fill="none" viewBox="0 0 24 24" stroke="currentColor">
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
                'target' => '_blank',
            ]);

            if ($user->hasPermissionTo('view_analytics')) {
                $analyticsIcon = '<svg class=' . $svgClass . ' fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z" />
                 </svg>';
                $analytics = $item->add($analyticsIcon, ['route' => 'shopper.analytics', 'class' => $class]);
                $analytics->link->attr([
                    'class' => '',
                    'data-toggle' => 'tooltip',
                    'data-title' => __('Analytics'),
                    'title' => __('Analytics'),
                ]);
            }
        });

        $view->with('menu', $menu);
    }
}
