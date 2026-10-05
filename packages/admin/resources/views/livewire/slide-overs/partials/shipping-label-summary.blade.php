<div>
    <h3 class="text-sh-fg text-sm font-semibold">
        {{ __('shopper::words.summary') }}
    </h3>
    <dl class="mt-3 space-y-2">
        <div class="flex items-center justify-between text-sm">
            <dt class="text-sh-fg-muted">{{ __('shopper::words.subtotal') }}</dt>
            <dd class="text-sh-fg font-medium">
                {{ shopper_money_format(amount: $this->order->total(), currency: $this->order->currency_code) }}
            </dd>
        </div>
        @if ($this->order->shippingOption)
            <div class="flex items-center justify-between text-sm">
                <dt class="text-sh-fg-muted">{{ __('shopper::words.shipping') }}</dt>
                <dd class="text-sh-fg font-medium">
                    {{ shopper_money_format(amount: $this->order->shippingOption->price, currency: $this->order->currency_code) }}
                </dd>
            </div>
        @endif

        <div class="border-sh-border flex items-center justify-between border-t pt-2 text-sm">
            <dt class="text-sh-fg font-semibold">{{ __('shopper::words.total') }}</dt>
            <dd class="text-sh-fg font-semibold">
                {{ shopper_money_format(amount: $this->order->total() + ($this->order->shippingOption?->price ?? 0), currency: $this->order->currency_code) }}
            </dd>
        </div>
    </dl>
</div>
