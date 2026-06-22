<?php

declare(strict_types=1);

namespace Shopper\Listeners\Notifications;

use Filament\Notifications\Notification;
use Filament\Support\Icons\Heroicon;
use Shopper\Core\Events\Orders\OrderPaid;

final class SendOrderPaidNotification extends SendsOrderNotification
{
    public function handle(OrderPaid $event): void
    {
        $order = $event->order;

        $this->send(
            Notification::make()
                ->title(__('shopper::notifications.database.order_paid.title'))
                ->body(__('shopper::notifications.database.order_paid.body', ['number' => $order->number]))
                ->icon(Heroicon::OutlinedBanknotes)
                ->success(),
            $order,
        );
    }
}
