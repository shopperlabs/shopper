<?php

declare(strict_types=1);

namespace Shopper\Listeners\Notifications;

use Filament\Notifications\Notification;
use Filament\Support\Icons\Heroicon;
use Shopper\Core\Events\Orders\OrderCreated;

final class SendNewOrderNotification extends SendsOrderNotification
{
    public function handle(OrderCreated $event): void
    {
        $order = $event->order;

        $this->send(
            Notification::make()
                ->title(__('shopper::notifications.database.order_created.title'))
                ->body(__('shopper::notifications.database.order_created.body', ['number' => $order->number]))
                ->icon(Heroicon::OutlinedShoppingBag)
                ->success(),
            $order,
        );
    }
}
