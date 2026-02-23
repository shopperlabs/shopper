<?php

declare(strict_types=1);

namespace Shopper\Livewire\Components\Orders;

use Illuminate\Contracts\View\View;
use Livewire\Component;
use Shopper\Core\Models\Contracts\Order;
use Shopper\Payment\Facades\Payment;
use Shopper\Shipping\Facades\Shipping;

class OrderSummary extends Component
{
    public Order $order;

    public function render(): View
    {
        $shippingOption = $this->order->shippingOption;
        $carrier = $shippingOption?->carrier;
        $paymentMethod = $this->order->paymentMethod;
        $subtotal = $this->order->total();
        $shippingPrice = $shippingOption?->price ?? 0; // @phpstan-ignore nullsafe.neverNull

        return view('shopper::livewire.components.orders.order-summary', [
            'subtotal' => $subtotal,
            'shippingPrice' => $shippingPrice,
            'shippingOption' => $shippingOption,
            'carrierLogoUrl' => $carrier?->logoUrl()
                ?? ($carrier ? Shipping::driver($carrier->driver ?? 'manual')->logo() : null),
            'paymentLogoUrl' => $paymentMethod?->logoUrl()
                ?? ($paymentMethod ? Payment::driver($paymentMethod->driver ?? 'manual')->logo() : null),
            'itemsCount' => $this->order->items->count(),
            'total' => $subtotal + $shippingPrice,
        ]);
    }
}
