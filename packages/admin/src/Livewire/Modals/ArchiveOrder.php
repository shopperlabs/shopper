<?php

declare(strict_types=1);

namespace Shopper\Livewire\Modals;

use Illuminate\Contracts\View\View;
use Shopper\Core\Models\Order;
use Shopper\Livewire\Components\ModalComponent;

class ArchiveOrder extends ModalComponent
{
    public Order $order;

    public function archived(): void
    {
        $this->order->delete();

        session()->flash('success', __('shopper::notifications.orders.archived'));

        $this->redirectRoute('shopper.orders.index', navigate: true);
    }

    public static function modalMaxWidth(): string
    {
        return 'xl';
    }

    public function render(): View
    {
        return view('shopper::livewire.modals.archive-order');
    }
}
