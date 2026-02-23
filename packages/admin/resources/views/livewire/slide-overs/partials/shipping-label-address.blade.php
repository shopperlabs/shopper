<div class="border-t border-gray-100 pt-4 dark:border-white/10">
    <h3 class="text-sm font-semibold text-gray-900 dark:text-white">
        {{ __('shopper::pages/orders.shipping_address') }}
    </h3>
    @if ($this->order->shippingAddress)
        <div class="mt-3 text-sm text-gray-500 dark:text-gray-400">
            <p class="font-medium text-gray-900 dark:text-white">
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
