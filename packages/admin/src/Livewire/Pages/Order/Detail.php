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
use Livewire\Attributes\Computed;
use Livewire\Attributes\Validate;
use Livewire\WithPagination;
use Mckenziearts\Icons\Untitledui\Enums\Untitledui;
use Shopper\Core\Enum\OrderStatus;
use Shopper\Core\Events\Orders\AddNoteToOrder;
use Shopper\Core\Events\Orders\OrderArchived;
use Shopper\Core\Events\Orders\OrderCancel;
use Shopper\Core\Events\Orders\OrderCompleted;
use Shopper\Core\Events\Orders\OrderPaid;
use Shopper\Core\Events\Orders\OrderRegistered;
use Shopper\Core\Models\Contracts\Order;
use Shopper\Core\Models\Contracts\ShopperUser;
use Shopper\Livewire\Pages\AbstractPageComponent;

/**
 * @property-read ShopperUser|null $customer
 */
class Detail extends AbstractPageComponent implements HasActions, HasSchemas
{
    use InteractsWithActions;
    use InteractsWithSchemas;
    use WithPagination;

    public Order $order;

    public int $perPage = 3;

    #[Validate('required|string')]
    public ?string $notes = null;

    public function mount(): void
    {
        $this->authorize('read_orders');

        $this->order->load('items', 'shippingAddress', 'billingAddress');
    }

    public function goToOrder(int $id): void
    {
        $this->redirectRoute('shopper.orders.detail', $id, navigate: true);
    }

    public function leaveNotes(): void
    {
        $this->validate();

        $this->order->update(['notes' => $this->notes]);

        event(new AddNoteToOrder($this->order));

        Notification::make()
            ->body(__('shopper::pages/orders.notifications.note_added'))
            ->success()
            ->send();
    }

    #[Computed]
    public function customer(): ?ShopperUser
    {
        $userModel = config('auth.providers.users.model');

        return $userModel::query()
            ->withCount('orders')
            ->find($this->order->customer_id);
    }

    public function cancelOrderAction(): Action
    {
        return Action::make('cancelOrder')
            ->label(__('shopper::forms.actions.cancel_order'))
            ->visible($this->order->canBeCancelled())
            ->action(function (): void {
                $this->order->update(['status' => OrderStatus::Cancelled]);

                event(new OrderCancel($this->order));

                Notification::make()
                    ->body(__('shopper::pages/orders.notifications.cancelled'))
                    ->success()
                    ->send();
            });
    }

    public function registerAction(): Action
    {
        return Action::make('register')
            ->label(__('shopper-core::status.registered'))
            ->visible($this->order->isPending())
            ->action(function (): void {
                $this->order->update(['status' => OrderStatus::Register]);

                event(new OrderRegistered($this->order));

                Notification::make()
                    ->body(__('shopper::pages/orders.notifications.registered'))
                    ->success()
                    ->send();
            });
    }

    public function markPaidAction(): Action
    {
        return Action::make('markPaid')
            ->label(__('shopper::forms.actions.mark_paid'))
            ->visible($this->order->isPending() || $this->order->isRegister())
            ->action(function (): void {
                $this->order->update(['status' => OrderStatus::Paid]);

                event(new OrderPaid($this->order));

                Notification::make()
                    ->body(__('shopper::pages/orders.notifications.paid'))
                    ->success()
                    ->send();
            });
    }

    public function markCompleteAction(): Action
    {
        return Action::make('markComplete')
            ->label(__('shopper::forms.actions.mark_complete'))
            ->visible($this->order->isPaid())
            ->action(function (): void {
                $this->order->update(['status' => OrderStatus::Completed]);

                event(new OrderCompleted($this->order));

                Notification::make()
                    ->body(__('shopper::pages/orders.notifications.completed'))
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
                event(new OrderArchived($this->order));

                $this->order->update([
                    'status' => OrderStatus::Register,
                ]);

                Notification::make()
                    ->title(__('shopper::notifications.orders.archived'))
                    ->success()
                    ->send();

                $this->redirectRoute('shopper.orders.index', navigate: true);
            });
    }

    public function render(): View
    {
        return view('shopper::livewire.pages.orders.detail', [
            'items' => $this->order
                ->items()
                ->with('product', 'product.media', 'product.prices')
                ->simplePaginate($this->perPage),
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
