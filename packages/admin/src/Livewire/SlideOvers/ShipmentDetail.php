<?php

declare(strict_types=1);

namespace Shopper\Livewire\SlideOvers;

use Illuminate\Contracts\View\View;
use Illuminate\Database\Eloquent\Collection;
use Laravelcm\LivewireSlideOvers\SlideOverComponent;
use Livewire\Attributes\Computed;
use Livewire\Attributes\Locked;
use Livewire\Attributes\On;
use Shopper\Core\Models\OrderShipping;
use Shopper\Core\Models\OrderShippingEvent;
use Shopper\Traits\HandlesAuthorizationExceptions;

/**
 * @property-read Collection<int, OrderShippingEvent> $events
 */
class ShipmentDetail extends SlideOverComponent
{
    use HandlesAuthorizationExceptions;

    #[Locked]
    public OrderShipping $shipment;

    public static function panelMaxWidth(): string
    {
        return '2xl';
    }

    public function mount(): void
    {
        $this->authorize('orders.read');

        $this->shipment->load('events');
    }

    /** @return Collection<int, OrderShippingEvent> */
    #[Computed]
    public function events(): Collection
    {
        return $this->shipment->events()->orderBy('occurred_at')->get();
    }

    #[On('shipment-updated')]
    public function render(): View
    {
        $this->shipment->loadMissing('order.shippingAddress', 'carrier', 'items.product');

        return view('shopper::livewire.slide-overs.shipment-detail');
    }
}
