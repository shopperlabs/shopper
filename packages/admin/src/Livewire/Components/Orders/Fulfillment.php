<?php

declare(strict_types=1);

namespace Shopper\Livewire\Components\Orders;

use BackedEnum;
use Illuminate\Contracts\View\View;
use Livewire\Attributes\On;
use Livewire\Component;
use Shopper\Core\Enum\OrderStatus;
use Shopper\Core\Enum\ShippingStatus;
use Shopper\Core\Models\Contracts\Order;
use Shopper\Core\Models\Country;

class Fulfillment extends Component
{
    public Order $order;

    public function hasUnfulfilledItems(): bool
    {
        return $this->order->items()
            ->whereNull('order_shipping_id')
            ->exists();
    }

    public function openShippingLabel(): void
    {
        $this->dispatch(
            'openPanel',
            component: 'shopper-slide-overs.create-shipping-label',
            arguments: ['order' => $this->order->id],
        );
    }

    #[On('order.updated')]
    #[On('order.shipping.created')]
    public function render(): View
    {
        $this->order->loadMissing('shippingAddress');

        $shippingAddress = $this->order->shippingAddress;

        $country = $shippingAddress
            ? Country::query()->where('name', $shippingAddress->country_name)->first()
            : null;

        return view('shopper::livewire.components.orders.fulfillment', [
            'shippingAddress' => $shippingAddress,
            'country' => $country,
            'currentStep' => $this->resolveCurrentStep(),
            'steps' => $this->steps(),
        ]);
    }

    /**
     * @return array<int, array{label: string, icon: string|BackedEnum}>
     */
    private function steps(): array
    {
        return [
            ['label' => OrderStatus::New->getLabel(), 'icon' => OrderStatus::New->getIcon()],
            ['label' => OrderStatus::Processing->getLabel(), 'icon' => OrderStatus::Processing->getIcon()],
            ['label' => ShippingStatus::Shipped->getLabel(), 'icon' => ShippingStatus::Shipped->getIcon()],
            ['label' => OrderStatus::Completed->getLabel(), 'icon' => OrderStatus::Completed->getIcon()],
        ];
    }

    private function resolveCurrentStep(): int
    {
        if ($this->order->status === OrderStatus::Cancelled || $this->order->status === OrderStatus::Archived) {
            return 0;
        }

        return match (true) {
            $this->order->isCompleted() => 4,
            $this->order->shipping_status !== ShippingStatus::Unfulfilled => 3,
            $this->order->isProcessing() => 2,
            default => 1,
        };
    }
}
