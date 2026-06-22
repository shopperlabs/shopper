<?php

declare(strict_types=1);

namespace Shopper\Listeners\Notifications;

use Filament\Notifications\Notification;
use Filament\Support\Icons\Heroicon;
use Shopper\Payment\Enum\TransactionType;
use Shopper\Payment\Events\PaymentFailed;

final class SendPaymentFailedNotification extends SendsOrderNotification
{
    public function handle(PaymentFailed $event): void
    {
        $order = $event->order;
        $key = $event->type === TransactionType::Refund ? 'refund_failed' : 'payment_failed';

        $this->send(
            Notification::make()
                ->title(__("shopper::notifications.database.{$key}.title"))
                ->body(__("shopper::notifications.database.{$key}.body", ['number' => $order->number]))
                ->icon(Heroicon::OutlinedExclamationTriangle)
                ->danger(),
            $order,
        );
    }
}
