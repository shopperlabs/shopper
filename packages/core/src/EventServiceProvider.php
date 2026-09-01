<?php

declare(strict_types=1);

namespace Shopper\Core;

use Illuminate\Foundation\Support\Providers\EventServiceProvider as ServiceProvider;
use Shopper\Core\Events\Orders\OrderCancelled;
use Shopper\Core\Events\Orders\OrderPaid;
use Shopper\Core\Listeners\Orders\CompleteFulfilledOrderListener;
use Shopper\Core\Listeners\Orders\ReleaseCampaignBudgetListener;
use Shopper\Core\Listeners\Orders\RestoreOrderStockListener;

final class EventServiceProvider extends ServiceProvider
{
    /** @var array<class-string, array<int, class-string>> */
    protected $listen = [
        OrderCancelled::class => [
            RestoreOrderStockListener::class,
            ReleaseCampaignBudgetListener::class,
        ],
        OrderPaid::class => [
            CompleteFulfilledOrderListener::class,
        ],
    ];
}
