<?php

namespace Shopper\Framework\Http\Livewire\Modals;

use LivewireUI\Modal\ModalComponent;
use Shopper\Framework\Models\Shop\Order\Order;

class ArchiveOrder extends ModalComponent
{
    public Order $order;

    public function mount(int $id)
    {
        $this->order = Order::find($id);
    }

    public function archived()
    {
        $this->order->delete();

        session()->flash('success', __('Order successfully archived'));

        $this->redirectRoute('shopper.orders.index');
    }

    public static function modalMaxWidth(): string
    {
        return 'lg';
    }

    public function render()
    {
        return view('shopper::livewire.modals.archive-order');
    }
}
