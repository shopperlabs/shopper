<div class="border-sh-border border-t pt-4">
    <h3 class="text-sh-fg text-sm font-semibold">
        {{ __('shopper::pages/orders.shipping_address') }}
    </h3>
    @if ($this->order->shippingAddress)
        <div class="text-sh-fg-muted mt-3 text-sm">
            <p class="text-sh-fg font-medium">
                {{ $this->order->shippingAddress->full_name }}
            </p>
            <p>{{ $this->order->shippingAddress->street_address }}</p>
            @if ($this->order->shippingAddress->street_address_plus)
                <p>{{ $this->order->shippingAddress->street_address_plus }}</p>
            @endif

            <p>{{ $this->order->shippingAddress->postal_code }} {{ $this->order->shippingAddress->city }}</p>
            @if ($this->order->shippingAddress->country_name)
                <p>{{ $this->order->shippingAddress->country_name }}</p>
            @endif
        </div>
    @endif
</div>
