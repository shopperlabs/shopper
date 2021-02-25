<?php

namespace Shopper\Framework\Http\Livewire\Orders;

use Livewire\Component;
use Livewire\WithPagination;
use Shopper\Framework\Models\Shop\Order\Order;
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
    public $perPage = 3;

    /**
     * Customer order notes.
     *
     * @var string
     */
    public $notes;

    /**
     * Redirect to the specific order.
     *
     * @param  int  $id
     * @return void
     */
    public function goToOrder(int $id)
    {
        $this->redirectRoute('shopper.orders.show', $id);
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
            'message' => __('Your note has been added and will be emailed to the user on their order.')
        ]);
    }

    /**
     * Render the component.
     *
     * @return \Illuminate\View\View
     */
    public function render()
    {
        return view('shopper::livewire.orders.show', [
            'items' => $this->order->items()->simplePaginate($this->perPage),
            'nextOrder' => Order::query()->where('id', '>', $this->order->id)->oldest('id')->first() ?? null,
            'prevOrder' => Order::query()->where('id', '<', $this->order->id)->latest('id')->first() ?? null,
            'billingAddress' => Address::query()
                ->where('user_id', $this->order->customer->id)
                ->where('type', Address::TYPE_BILLING)
                ->where('is_default', true)
                ->first()
        ]);
    }
}
