<?php

declare(strict_types=1);

namespace Shopper\Http\Livewire\Components\Orders;

use Filament\Notifications\Notification;
use Illuminate\Contracts\View\View;
use Livewire\Component;
use Livewire\WithPagination;
use Shopper\Core\Enum\OrderStatus;
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

        // TODO dispatch new event

        Notification::make()
            ->title(__('Cancelled'))
            ->body(__('This order has been cancelled'))
            ->success()
            ->send();
    }

    public function leaveNotes(): void
    {
        $this->validate(['notes' => 'required']);

        $this->order->update(['notes' => $this->notes]);

        // TODO dispatch new event

        Notification::make()
            ->title(__('Notes added'))
            ->body(__('Your note has been added and will be emailed to the user on their order.'))
            ->success()
            ->send();
    }

    public function register(): void
    {
        $this->order->update(['status' => OrderStatus::REGISTER->value]);

        // TODO dispatch new event

        Notification::make()
            ->title(__('Updated Status'))
            ->body(__('This order has been marked as register and notification has been sent to the customer by email'))
            ->success()
            ->send();
    }

    public function markPaid(): void
    {
        $this->order->update(['status' => OrderStatus::PAID->value]);

        // TODO dispatch new event

        Notification::make()
            ->title(__('Updated Status'))
            ->body(__('This order is marked as paid'))
            ->success()
            ->send();
    }

    public function markComplete(): void
    {
        $this->order->update(['status' => OrderStatus::COMPLETED->value]);

        // TODO dispatch new event

        Notification::make()
            ->title(__('Updated Status'))
            ->body(__('This order is marked as complete'))
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
