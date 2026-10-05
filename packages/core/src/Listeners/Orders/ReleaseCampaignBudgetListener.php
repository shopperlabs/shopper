<?php

declare(strict_types=1);

namespace Shopper\Core\Listeners\Orders;

use Illuminate\Contracts\Queue\ShouldQueue;
use Shopper\Core\Actions\ReleaseCampaignBudget;
use Shopper\Core\Events\Orders\OrderCancelled;

final class ReleaseCampaignBudgetListener implements ShouldQueue
{
    public function __construct(
        private readonly ReleaseCampaignBudget $releaseCampaignBudget,
    ) {}

    public function handle(OrderCancelled $event): void
    {
        $this->releaseCampaignBudget->releaseOrder($event->order->getKey(), actor: 'order-cancelled');
    }
}
