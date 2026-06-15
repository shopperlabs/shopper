<?php

declare(strict_types=1);

namespace Shopper\Core;

use Illuminate\Foundation\Support\Providers\EventServiceProvider as ServiceProvider;
use Shopper\Core\Events\Orders\OrderCancelled;
use Shopper\Core\Events\Orders\OrderItemCreated;
use Shopper\Core\Listeners\Orders\ReleaseCampaignBudgetListener;
use Shopper\Core\Listeners\Orders\RestoreOrderStockListener;
use Shopper\Core\Listeners\Orders\ReserveOrderItemStockListener;

final class EventServiceProvider extends ServiceProvider
{
    /** @var array<class-string, array<int, class-string>> */
    protected $listen = [
        OrderItemCreated::class => [
            ReserveOrderItemStockListener::class,
        ],
        OrderCancelled::class => [
            RestoreOrderStockListener::class,
            ReleaseCampaignBudgetListener::class,
        ],
    ];
}
