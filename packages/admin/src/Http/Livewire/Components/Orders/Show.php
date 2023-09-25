<?php

declare(strict_types=1);

namespace Shopper\Http\Livewire\Components\Orders;

use Filament\Notifications\Notification;
use Illuminate\Contracts\View\View;
use Livewire\Component;
use Livewire\WithPagination;
use Shopper\Core\Enum\OrderStatus;
use Shopper\Core\Events\Orders\AddNote;
use Shopper\Core\Events\Orders\Cancel;
use Shopper\Core\Events\Orders\Completed;
use Shopper\Core\Events\Orders\Paid;
use Shopper\Core\Events\Orders\Registered;
use Shopper\Core\Models\Address;
use Shopper\Core\Models\Order;

class Show extends Component
{
    use WithPagination;

    public Order $order;

    public int $perPage = 3;

    public ?string $notes = null;

    public function goToOrder(int $id): void
    {
        $this->redirectRoute('shopper.orders.show', $id);
    }

    public function cancelOrder(): void
    {
        $this->order->update(['status' => OrderStatus::CANCELLED->value]);

        event(new Cancel($this->order));

        Notification::make()
            ->body(__('shopper::pages/orders.notifications.cancelled'))
            ->success()
            ->send();
    }

    public function leaveNotes(): void
    {
        $this->validate(['notes' => 'required']);

        $this->order->update(['notes' => $this->notes]);

        event(new AddNote($this->order));

        Notification::make()
            ->body(__('shopper::pages/orders.notifications.note_added'))
            ->success()
            ->send();
    }

    public function register(): void
    {
        $this->order->update(['status' => OrderStatus::REGISTER->value]);

        event(new Registered($this->order));

        Notification::make()
            ->body(__('shopper::pages/orders.notifications.registered'))
            ->success()
            ->send();
    }

    public function markPaid(): void
    {
        $this->order->update(['status' => OrderStatus::PAID->value]);

        event(new Paid($this->order));

        Notification::make()
            ->body(__('shopper::pages/orders.notifications.paid'))
            ->success()
            ->send();
    }

    public function markComplete(): void
    {
        $this->order->update(['status' => OrderStatus::COMPLETED->value]);

        event(new Completed($this->order));

        Notification::make()
            ->body(__('shopper::pages/orders.notifications.completed'))
            ->success()
            ->send();
    }

    public function render(): View
    {
        return view('shopper::livewire.orders.show', [
            'items' => $this->order
                ->items()
                ->with('product')
                ->simplePaginate($this->perPage),
            'nextOrder' => Order::query()
                ->where('id', '>', $this->order->id)
                ->oldest('id')
                ->first() ?? null,
            'prevOrder' => Order::query()
                ->where('id', '<', $this->order->id)
                ->latest('id')
                ->first() ?? null,
            'billingAddress' => Address::query()
                ->where('user_id', $this->order->customer->id)
                ->where('type', Address::TYPE_BILLING)
                ->where('is_default', true)
                ->first(),
        ]);
    }
}
