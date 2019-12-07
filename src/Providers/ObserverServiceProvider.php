<?php

namespace Shopper\Framework\Providers;

use Illuminate\Support\ServiceProvider;
use Shopper\Framework\Models\Shop\Shop;
use Shopper\Framework\Observers\ShopObserver;

class ObserverServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        Shop::observe(ShopObserver::class);
    }
}
