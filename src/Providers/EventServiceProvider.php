<?php

namespace Shopper\Framework\Providers;

use Illuminate\Foundation\Support\Providers\EventServiceProvider as ServiceProvider;
use Shopper\Framework\Events\ShopCreated;
use Shopper\Framework\Listeners\ChannelSubscriber;
use Shopper\Framework\Listeners\InventorySubscriber;

class EventServiceProvider extends ServiceProvider
{
    /**
     * The event listener mappings for the application.
     *
     * @var array
     */
    protected $listen = [
        ShopCreated::class => [
            ChannelSubscriber::class,
            InventorySubscriber::class
        ],
    ];

    /**
     * Register any events for your application.
     *
     * @return void
     */
    public function boot()
    {
        parent::boot();

        //
    }
}
