<?php

declare(strict_types=1);

namespace Shopper\Livewire\Modals;

use Illuminate\Contracts\View\View;
use LivewireUI\Modal\ModalComponent;
use Shopper\Core\Models\Order;

class ArchiveOrder extends ModalComponent
{
    public Order $order;

    public function mount(int $id): void
    {
        $this->order = Order::find($id);
    }

    public function archived(): void
    {
        $this->order->delete();

        session()->flash('success', __('shopper::notifications.orders.archived'));

        $this->redirectRoute('shopper.orders.index');
    }

    public static function modalMaxWidth(): string
    {
        return 'lg';
    }

    public function render(): View
    {
        return view('shopper::livewire.modals.archive-order');
    }
}
