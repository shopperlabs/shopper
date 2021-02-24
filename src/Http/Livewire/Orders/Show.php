<?php

namespace Shopper\Framework\Http\Livewire\Orders;

use Livewire\Component;
use Shopper\Framework\Models\Shop\Order\Order;
use Shopper\Framework\Models\User\Address;

class Show extends Component
{
    /**
     * Current viewer order.
     *
     * @var Order
     */
    public Order $order;

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
     * Render the component.
     *
     * @return \Illuminate\View\View
     */
    public function render()
    {
        return view('shopper::livewire.orders.show', [
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
