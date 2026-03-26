<?php

declare(strict_types=1);

namespace Shopper\Livewire\SlideOvers;

use Filament\Actions\Action;
use Filament\Actions\Concerns\InteractsWithActions;
use Filament\Actions\Contracts\HasActions;
use Filament\Schemas\Concerns\InteractsWithSchemas;
use Filament\Schemas\Contracts\HasSchemas;
use Filament\Support\Enums\Size;
use Illuminate\Contracts\View\View;
use Illuminate\Database\Eloquent\Collection;
use Laravelcm\LivewireSlideOvers\SlideOverComponent;
use Livewire\Attributes\Computed;
use Livewire\Attributes\On;
use Mckenziearts\Icons\Untitledui\Enums\Untitledui;
use Shopper\Core\Models\OrderShipping;
use Shopper\Core\Models\OrderShippingEvent;
use Shopper\Traits\HandlesAuthorizationExceptions;

/**
 * @property-read Collection<int, OrderShippingEvent> $events
 */
class ShipmentDetail extends SlideOverComponent implements HasActions, HasSchemas
{
    use HandlesAuthorizationExceptions;
    use InteractsWithActions;
    use InteractsWithSchemas;

    public OrderShipping $shipment;

    public static function panelMaxWidth(): string
    {
        return '3xl';
    }

    public function mount(): void
    {
        $this->authorize('read_orders');

        $this->shipment->load('events');
    }

    /** @return Collection<int, OrderShippingEvent> */
    #[Computed]
    public function events(): Collection
    {
        return $this->shipment->events()->orderBy('occurred_at')->get();
    }

    public function addEventAction(): Action
    {
        $allowedTransitions = $this->shipment->allowedTransitions();

        return Action::make('addEvent')
            ->label(__('shopper::pages/orders.shipment.add_event'))
            ->icon(Untitledui::Plus)
            ->size(Size::Small)
            ->color('gray')
            ->visible(count($allowedTransitions) > 0)
            ->action(fn () => $this->dispatch(
                'openPanel',
                component: 'shopper-slide-overs.shipment-add-event',
                arguments: ['shipment' => $this->shipment],
            ));
    }

    #[On('shipment-updated')]
    public function render(): View
    {
        $this->shipment->loadMissing('order.shippingAddress', 'carrier', 'items.product');

        return view('shopper::livewire.slide-overs.shipment-detail');
    }
}
