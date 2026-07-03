<?php

declare(strict_types=1);

namespace Tests\Admin\Addon\Stubs;

use Illuminate\Support\Facades\Route;
use Shopper\Addon\BaseAddon;
use Shopper\ShopperPanel;

final class RoutingAddon extends BaseAddon
{
    public function getId(): string
    {
        return 'routing-stub';
    }

    public function register(ShopperPanel $panel): void
    {
        $panel->addonRoutes(function (): void {
            Route::prefix('setting')
                ->as('settings.')
                ->group(fn () => Route::get('/addon-probe', fn (): string => 'ok')->name('addon-probe'));
        });
    }
}
