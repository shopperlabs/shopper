<?php

declare(strict_types=1);

namespace Shopper\Listeners\Notifications;

use Filament\Actions\Action;
use Filament\Notifications\Notification;
use Filament\Support\Icons\Heroicon;
use Illuminate\Contracts\Queue\ShouldQueue;
use Shopper\Concerns\ResolvesAdministrators;
use Shopper\Core\Events\Products\ProductImportCompleted;

final class SendProductImportCompletedNotification implements ShouldQueue
{
    use ResolvesAdministrators;

    public string $queue = 'notifications';

    public function handle(ProductImportCompleted $event): void
    {
        $import = $event->import;
        $recipients = $this->administrators();

        if ($recipients->isEmpty()) {
            return;
        }

        $notification = Notification::make()
            ->title(__('shopper::notifications.database.product_import.title'))
            ->body(__('shopper::notifications.database.product_import.body', [
                'imported' => $import->imported_count,
                'failed' => $import->failed_count,
            ]))
            ->icon(Heroicon::OutlinedArrowDownTray);

        $import->failed_count > 0 ? $notification->warning() : $notification->success();

        $notification
            ->actions([
                Action::make('view')
                    ->label(__('shopper::notifications.database.view_products'))
                    ->url(route('shopper.products.index'))
                    ->markAsRead(),
            ])
            ->sendToDatabase($recipients, isEventDispatched: true);
    }
}
