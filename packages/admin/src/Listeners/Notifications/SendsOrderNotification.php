<?php

declare(strict_types=1);

namespace Shopper\Listeners\Notifications;

use Filament\Actions\Action;
use Filament\Notifications\Notification;
use Illuminate\Contracts\Queue\ShouldQueue;
use Shopper\Concerns\ResolvesAdministrators;
use Shopper\Core\Models\Contracts\Order;

abstract class SendsOrderNotification implements ShouldQueue
{
    use ResolvesAdministrators;

    public string $queue = 'notifications';

    protected function send(Notification $notification, Order $order): void
    {
        $recipients = $this->administrators();

        if ($recipients->isEmpty()) {
            return;
        }

        $notification
            ->actions([
                Action::make('view')
                    ->label(__('shopper::notifications.database.view_order'))
                    ->url(route('shopper.orders.detail', $order))
                    ->markAsRead(),
            ])
            ->sendToDatabase($recipients, isEventDispatched: true);
    }
}
