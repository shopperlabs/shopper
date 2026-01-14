<?php

declare(strict_types=1);

namespace Shopper\Livewire\Modals;

use Illuminate\Contracts\View\View;
use Shopper\Core\Events\Orders\OrderArchived;
use Shopper\Core\Models\Contracts\Order as OrderContract;
use Shopper\Livewire\Components\ModalComponent;

class ArchiveOrder extends ModalComponent
{
    public OrderContract $order;

    public static function modalMaxWidth(): string
    {
        return 'xl';
    }

    public function archived(): void
    {
        $this->order->delete();

        event(new OrderArchived($this->order));

        session()->flash('success', __('shopper::notifications.orders.archived'));

        $this->redirectRoute('shopper.orders.index', navigate: true);
    }

    public function render(): View
    {
        return view('shopper::livewire.modals.archive-order');
    }
}
