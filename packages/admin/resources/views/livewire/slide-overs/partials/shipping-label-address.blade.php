<div class="border-t border-sh-border pt-4">
    <h3 class="text-sm font-semibold text-sh-fg">
        {{ __('shopper::pages/orders.shipping_address') }}
    </h3>
    @if ($this->order->shippingAddress)
        <div class="mt-3 text-sm text-sh-fg-muted">
            <p class="font-medium text-sh-fg">
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
