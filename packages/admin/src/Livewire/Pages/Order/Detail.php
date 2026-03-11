<?php

declare(strict_types=1);

namespace Shopper\Livewire\Pages\Order;

use Filament\Actions\Action;
use Filament\Actions\Concerns\InteractsWithActions;
use Filament\Actions\Contracts\HasActions;
use Filament\Notifications\Notification;
use Filament\Schemas\Concerns\InteractsWithSchemas;
use Filament\Schemas\Contracts\HasSchemas;
use Illuminate\Contracts\View\View;
use Livewire\Attributes\On;
use Mckenziearts\Icons\Untitledui\Enums\Untitledui;
use Shopper\Core\Enum\OrderStatus;
use Shopper\Core\Enum\PaymentStatus;
use Shopper\Core\Events\Orders\OrderArchived;
use Shopper\Core\Events\Orders\OrderCancelled;
use Shopper\Core\Events\Orders\OrderCompleted;
use Shopper\Core\Events\Orders\OrderPaid;
use Shopper\Core\Models\Contracts\Order;
use Shopper\Livewire\Pages\AbstractPageComponent;
use Shopper\Payment\Services\PaymentProcessingService;
use Shopper\Traits\HandlesAuthorizationExceptions;

class Detail extends AbstractPageComponent implements HasActions, HasSchemas
{
    use HandlesAuthorizationExceptions;
    use InteractsWithActions;
    use InteractsWithSchemas;

    public Order $order;

    public function mount(): void
    {
        $this->authorize('read_orders');

        $this->order->load('customer', 'channel');
    }

    public function goToOrder(int $id): void
    {
        $this->redirectRoute('shopper.orders.detail', $id, navigate: true);
    }

    public function cancelOrderAction(): Action
    {
        return Action::make('cancelOrder')
            ->label(__('shopper::forms.actions.cancel_order'))
            ->visible($this->order->canBeCancelled())
            ->action(function (): void {
                $this->order->update([
                    'status' => OrderStatus::Cancelled,
                    'cancelled_at' => now(),
                ]);

                $this->order->refresh();
                $this->dispatch('order.updated');

                event(new OrderCancelled($this->order));

                Notification::make()
                    ->title(__('shopper::pages/orders.notifications.cancelled'))
                    ->success()
                    ->send();
            });
    }

    public function startProcessingAction(): Action
    {
        return Action::make('startProcessing')
            ->label(__('shopper::forms.actions.start_processing'))
            ->visible($this->order->isNew())
            ->action(function (): void {
                $this->order->update(['status' => OrderStatus::Processing]);

                $this->order->refresh();
                $this->dispatch('order.updated');

                Notification::make()
                    ->title(__('shopper::pages/orders.notifications.processing'))
                    ->success()
                    ->send();
            });
    }

    public function markPaidAction(): Action
    {
        return Action::make('markPaid')
            ->label(__('shopper::forms.actions.mark_paid'))
            ->visible($this->order->isPaymentPending() || $this->order->isPaymentAuthorized())
            ->action(function (): void {
                $data = ['payment_status' => PaymentStatus::Paid];

                if ($this->order->isNew()) {
                    $data['status'] = OrderStatus::Processing;
                }

                $this->order->update($data);

                $this->order->refresh();
                $this->dispatch('order.updated');

                event(new OrderPaid($this->order));

                Notification::make()
                    ->title(__('shopper::pages/orders.notifications.paid'))
                    ->success()
                    ->send();
            });
    }

    public function markCompleteAction(): Action
    {
        return Action::make('markComplete')
            ->label(__('shopper::forms.actions.mark_complete'))
            ->visible($this->order->isProcessing() && $this->order->isPaid())
            ->action(function (): void {
                $this->order->update(['status' => OrderStatus::Completed]);

                $this->order->refresh();
                $this->dispatch('order.updated');

                event(new OrderCompleted($this->order));

                Notification::make()
                    ->title(__('shopper::pages/orders.notifications.completed'))
                    ->success()
                    ->send();
            });
    }

    public function capturePaymentAction(): Action
    {
        return Action::make('capturePayment')
            ->label(__('shopper::forms.actions.capture_payment'))
            ->icon(Untitledui::CreditCardDown)
            ->visible($this->order->isPaymentAuthorized())
            ->requiresConfirmation()
            ->modalIcon(Untitledui::CreditCardDown)
            ->modalHeading(__('shopper::pages/orders.modals.capture_heading', ['number' => $this->order->number]))
            ->modalDescription(__('shopper::pages/orders.modals.capture_notice'))
            ->modalSubmitActionLabel(__('shopper::forms.actions.confirm'))
            ->action(function (): void {
                $service = resolve(PaymentProcessingService::class);
                $reference = $service->getLatestReference($this->order);

                if (! $reference) {
                    Notification::make()
                        ->title(__('shopper::pages/orders.notifications.capture_no_reference'))
                        ->danger()
                        ->send();

                    return;
                }

                $result = $service->capture($this->order, $reference);

                if (! $result->success) {
                    Notification::make()
                        ->title($result->message ?? __('shopper::pages/orders.notifications.capture_failed'))
                        ->danger()
                        ->send();

                    return;
                }

                $this->order->refresh();
                $this->dispatch('order.updated');

                Notification::make()
                    ->title(__('shopper::pages/orders.notifications.captured'))
                    ->success()
                    ->send();
            });
    }

    public function archiveAction(): Action
    {
        return Action::make('archive')
            ->label(__('shopper::forms.actions.archive'))
            ->color('danger')
            ->icon(Untitledui::Archive)
            ->visible(! $this->order->isCompleted() && ! $this->order->isPaid())
            ->requiresConfirmation()
            ->modalHeading(__('shopper::pages/orders.modals.archived_number', ['number' => $this->order->number]))
            ->modalDescription(__('shopper::pages/orders.modals.archived_notice'))
            ->modalSubmitActionLabel(__('shopper::forms.actions.confirm'))
            ->action(function (): void {
                $this->order->update([
                    'status' => OrderStatus::Archived,
                    'archived_at' => now(),
                ]);

                event(new OrderArchived($this->order));

                Notification::make()
                    ->title(__('shopper::notifications.orders.archived'))
                    ->success()
                    ->send();

                $this->redirectRoute('shopper.orders.index', navigate: true);
            });
    }

    #[On('order.shipping.created')]
    public function render(): View
    {
        return view('shopper::livewire.pages.orders.detail', [
            'nextOrder' => resolve(Order::class)::query()
                ->where('id', '>', $this->order->id)
                ->oldest('id')
                ->first(),
            'prevOrder' => resolve(Order::class)::query()
                ->where('id', '<', $this->order->id)
                ->latest('id')
                ->first(),
        ])
            ->title(__('shopper::pages/orders.show_title', ['number' => $this->order->number]));
    }
}
