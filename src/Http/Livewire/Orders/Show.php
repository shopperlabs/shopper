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

    /**
     * Current viewer order.
     *
     * @var Order
     */
    public Order $order;

    /**
     * Order item to display on the page.
     *
     * @var int
     */
    public int $perPage = 3;

    /**
     * Customer order notes.
     *
     * @var string
     */
    public $notes;

    /**
     * Confirm modal to archived order.
     *
     * @var bool
     */
    public $confirmationArchived = false;

    /**
     * Redirect to the specific order.
     *
     * @param  int  $id
     *
     * @return void
     */
    public function goToOrder(int $id)
    {
        $this->redirectRoute('shopper.orders.show', $id);
    }

    /**
     * Launch modal to archived the current order.
     *
     * @return void
     */
    public function openModal()
    {
        $this->confirmationArchived = true;
    }

    /**
     * Cancel action to archived order.
     *
     * @return void
     */
    public function cancel()
    {
        $this->confirmationArchived = false;
    }

    /**
     * Archived order.
     *
     * @return void
     *
     * @throws \Exception
     */
    public function archived()
    {
        $this->order->delete();

        session()->flash('success', __('Order successfully archived'));

        $this->redirectRoute('shopper.orders.index');
    }

    /**
     * Cancel order.
     *
     * @return void
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
     *
     * @return void
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

    /**
     * Register the order by changed order status.
     *
     * @return void
     */
    public function register()
    {
        $this->order->update(['status' => OrderStatus::REGISTER]);

        // Send notification to the customer

        $this->notify([
            'title' => __('Update Status'),
            'message' => __('This order has been marked as register and notification has been sent to the customer by email.'),
        ]);
    }

    /**
     * Mark the order as paid.
     *
     * @return void
     */
    public function markPaid()
    {
        $this->order->update(['status' => OrderStatus::PAID]);

        $this->notify([
            'title' => __('Update Status'),
            'message' => __('This order is marked as paid.'),
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
