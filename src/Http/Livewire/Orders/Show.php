<?php

declare(strict_types=1);

namespace Shopper\Framework\Http\Livewire\Orders;

use Illuminate\Contracts\View\View;
use Livewire\Component;
use Livewire\WithPagination;
use Shopper\Framework\Models\Shop\Order\Order;
use Shopper\Framework\Models\Shop\Order\OrderStatus;
use Shopper\Framework\Models\User\Address;
use WireUi\Traits\Actions;

class Show extends Component
{
    use Actions;
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
        $this->order->update(['status' => OrderStatus::CANCELLED]);

        $this->notification()->success(__('Cancelled'), __('This order has been cancelled!'));
    }

    public function leaveNotes(): void
    {
        $this->validate(['notes' => 'required']);

        $this->order->update(['notes' => $this->notes]);

        // TODO Send notification to the customer about order notes.

        $this->notification()->success(
            __('Notes added'),
            __('Your note has been added and will be emailed to the user on their order.')
        );
    }

    public function register(): void
    {
        $this->order->update(['status' => OrderStatus::REGISTER]);

        // TODO Send notification to the customer about order registration.

        $this->notification()->success(
            __('Updated Status'),
            __('This order has been marked as register and notification has been sent to the customer by email.')
        );
    }

    public function markPaid(): void
    {
        $this->order->update(['status' => OrderStatus::PAID]);

        $this->notification()->success(__('Updated Status'), __('This order is marked as paid!'));
    }

    public function markComplete(): void
    {
        $this->order->update(['status' => OrderStatus::COMPLETED]);

        $this->notification()->success(__('Updated Status'), __('This order is marked as complete.'));
    }

    public function render(): View
    {
        return view('shopper::livewire.orders.show', [
            'items' => $this->order->items()->with('product')->simplePaginate($this->perPage),
            'nextOrder' => Order::query()->where('id', '>', $this->order->id)->oldest('id')->first() ?? null,
            'prevOrder' => Order::query()->where('id', '<', $this->order->id)->latest('id')->first() ?? null,
            'billingAddress' => Address::query()
                ->where('user_id', $this->order->customer->id)
                ->where('type', Address::TYPE_BILLING)
                ->where('is_default', true)
                ->first(),
        ]);
    }
}
