<?php

declare(strict_types=1);

namespace Shopper\Livewire\Components\Orders;

use Illuminate\Contracts\View\View;
use Livewire\Component;
use Shopper\Core\Models\Contracts\Order;
use Shopper\Core\Models\Contracts\TaxZone;
use Shopper\Payment\Facades\Payment;
use Shopper\Shipping\Facades\Shipping;

class OrderSummary extends Component
{
    public Order $order;

    public function render(): View
    {
        $this->order->loadMissing(['shippingOption.carrier', 'paymentMethod', 'items', 'zone.countries']);

        $shippingOption = $this->order->shippingOption;
        $carrier = $shippingOption?->carrier;
        $paymentMethod = $this->order->paymentMethod;
        $subtotal = $this->order->total();
        $shippingPrice = $shippingOption?->price ?? 0; // @phpstan-ignore nullsafe.neverNull
        $taxAmount = $this->order->tax_amount ?? 0;
        $isTaxInclusive = $this->resolveTaxInclusivity();
        $divisor = is_no_division_currency($this->order->currency_code) ? 1 : 100;

        return view('shopper::livewire.components.orders.order-summary', [
            'subtotal' => $subtotal,
            'shippingPrice' => $shippingPrice,
            'shippingOption' => $shippingOption,
            'taxAmount' => $taxAmount,
            'isTaxInclusive' => $isTaxInclusive,
            'carrierLogoUrl' => $carrier?->logoUrl()
                ?? ($carrier ? Shipping::driver($carrier->driver ?? 'manual')->logo() : null),
            'paymentLogoUrl' => $paymentMethod?->logoUrl()
                ?? ($paymentMethod ? Payment::driver($paymentMethod->driver ?? 'manual')->logo() : null),
            'itemsCount' => $this->order->items->count(),
            'total' => $this->order->price_amount !== null
                ? $this->order->price_amount / $divisor
                : ($subtotal + $shippingPrice + ($isTaxInclusive ? 0 : $taxAmount)),
        ]);
    }

    private function resolveTaxInclusivity(): bool
    {
        $zone = $this->order->zone;

        if (! $zone) {
            return false;
        }

        $country = $zone->countries()->first();

        if (! $country) {
            return false;
        }

        $taxZone = resolve(TaxZone::class)::query()
            ->where('country_id', $country->id)
            ->first();

        if (! $taxZone) {
            return false;
        }

        return $taxZone->is_tax_inclusive;
    }
}
