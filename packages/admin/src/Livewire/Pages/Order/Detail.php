<?php

declare(strict_types=1);

namespace Shopper\Livewire\Pages\Order;

use Filament\Actions\Action;
use Filament\Actions\Concerns\InteractsWithActions;
use Filament\Actions\Contracts\HasActions;
use Filament\Forms\Concerns\InteractsWithForms;
use Filament\Forms\Contracts\HasForms;
use Filament\Notifications\Notification;
use Illuminate\Contracts\View\View;
use Livewire\Attributes\Computed;
use Livewire\Attributes\Validate;
use Livewire\WithPagination;
use Shopper\Core\Enum\OrderStatus;
use Shopper\Core\Events\Orders\AddNote;
use Shopper\Core\Events\Orders\Cancel;
use Shopper\Core\Events\Orders\Completed;
use Shopper\Core\Events\Orders\Paid;
use Shopper\Core\Events\Orders\Registered;
use Shopper\Core\Models\Order;
use Shopper\Core\Models\User;
use Shopper\Livewire\Pages\AbstractPageComponent;

/**
 * @property-read User|null $customer
 */
class Detail extends AbstractPageComponent implements HasActions, HasForms
{
    use InteractsWithActions;
    use InteractsWithForms;
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

        event(new AddNote($this->order));

        Notification::make()
            ->body(__('shopper::pages/orders.notifications.note_added'))
            ->success()
            ->send();
    }

    #[Computed(persist: true)]
    public function customer(): ?User
    {
        return User::query()
            ->withCount('orders')
            ->find($this->order->customer_id);
    }

    public function cancelOrderAction(): Action
    {
        return Action::make('cancelOrder')
            ->label(__('shopper::forms.actions.cancel_order'))
            ->visible($this->order->canBeCancelled())
            ->action(function (): void {
                $this->order->update(['status' => OrderStatus::Cancelled()]);

                event(new Cancel($this->order));

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
                $this->order->update(['status' => OrderStatus::Register()]);

                event(new Registered($this->order));

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
                $this->order->update(['status' => OrderStatus::Paid()]);

                event(new Paid($this->order));

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
                $this->order->update(['status' => OrderStatus::Completed()]);

                event(new Completed($this->order));

                Notification::make()
                    ->body(__('shopper::pages/orders.notifications.completed'))
                    ->success()
                    ->send();
            });
    }

    public function render(): View
    {
        return view('shopper::livewire.pages.orders.detail', [
            'items' => $this->order
                ->items()
                ->with('product', 'product.media', 'product.prices')
                ->simplePaginate($this->perPage),
            'nextOrder' => Order::query()
                ->where('id', '>', $this->order->id)
                ->oldest('id')
                ->first(),
            'prevOrder' => Order::query()
                ->where('id', '<', $this->order->id)
                ->latest('id')
                ->first(),
        ])
            ->title(__('shopper::pages/orders.show_title', ['number' => $this->order->number]));
    }
}
