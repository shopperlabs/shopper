<?php

namespace Shopper\Framework\Http\Livewire\Orders;

use Livewire\Component;
use Livewire\WithPagination;
use Shopper\Framework\Models\Shop\Order\Order;
use Shopper\Framework\Models\Shop\Order\OrderStatus;
use Shopper\Framework\Models\User\Address;

class Show extends Component
{
    use WithPagination;

    public Order $order;
    public int $perPage = 3;
    public ?string $notes = null;

    /**
     * Redirect to the specific order.
     */
    public function goToOrder(int $id)
    {
        $this->redirectRoute('shopper.orders.show', $id);
    }

    /**
     * Cancel order.
     */
    public function cancelOrder()
    {
        $this->order->update(['status' => OrderStatus::CANCELLED]);

        $this->notify([
            'title' => __('Cancelled'),
            'message' => __('This order has been cancelled.'),
        ]);
    }

    /**
     * Updated order with customer notes.
     */
    public function leaveNotes()
    {
        $this->validate(['notes' => 'required']);

        $this->order->update(['notes' => $this->notes]);

        $this->notify([
            'title' => __('Notes added'),
            'message' => __('Your note has been added and will be emailed to the user on their order.'),
        ]);
    }

    public function register()
    {
        $this->order->update(['status' => OrderStatus::REGISTER]);

        // Send notification to the customer

        $this->notify([
            'title' => __('Update Status'),
            'message' => __('This order has been marked as register and notification has been sent to the customer by email.'),
        ]);
    }

    public function markPaid()
    {
        $this->order->update(['status' => OrderStatus::PAID]);

        $this->notify([
            'title' => __('Update Status'),
            'message' => __('This order is marked as paid.'),
        ]);
    }

    public function markComplete()
    {
        $this->order->update(['status' => OrderStatus::COMPLETED]);

        $this->notify([
            'title' => __('Update Status'),
            'message' => __('This order is marked as complete.'),
        ]);
    }

    public function render()
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
