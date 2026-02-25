<?php

declare(strict_types=1);

namespace Shopper\Livewire\Components\Orders;

use Filament\Notifications\Notification;
use Illuminate\Contracts\View\View;
use Livewire\Attributes\Validate;
use Livewire\Component;
use Shopper\Core\Events\Orders\OrderNoteAdded;
use Shopper\Core\Models\Contracts\Order;

class OrderNotes extends Component
{
    public Order $order;

    #[Validate('required|string')]
    public ?string $notes = null;

    public function leaveNotes(): void
    {
        $this->validate();

        $this->order->update(['notes' => $this->notes]);

        event(new OrderNoteAdded($this->order));

        Notification::make()
            ->title(__('shopper::pages/orders.notifications.note_added'))
            ->success()
            ->send();
    }

    public function render(): View
    {
        return view('shopper::livewire.components.orders.order-notes');
    }
}
