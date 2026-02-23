<div>
    <h3 class="text-sm font-semibold text-gray-900 dark:text-white">
        {{ __('shopper::words.summary') }}
    </h3>
    <dl class="mt-3 space-y-2">
        <div class="flex items-center justify-between text-sm">
            <dt class="text-gray-500 dark:text-gray-400">{{ __('shopper::words.subtotal') }}</dt>
            <dd class="font-medium text-gray-900 dark:text-white">
                {{ shopper_money_format(amount: $this->order->total(), currency: $this->order->currency_code) }}
            </dd>
        </div>
        @if ($this->order->shippingOption)
            <div class="flex items-center justify-between text-sm">
                <dt class="text-gray-500 dark:text-gray-400">{{ __('shopper::words.shipping') }}</dt>
                <dd class="font-medium text-gray-900 dark:text-white">
                    {{ shopper_money_format(amount: $this->order->shippingOption->price, currency: $this->order->currency_code) }}
                </dd>
            </div>
        @endif
        <div class="flex items-center justify-between border-t border-gray-100 pt-2 text-sm dark:border-white/10">
            <dt class="font-semibold text-gray-900 dark:text-white">{{ __('shopper::words.total') }}</dt>
            <dd class="font-semibold text-gray-900 dark:text-white">
                {{ shopper_money_format(amount: $this->order->total() + ($this->order->shippingOption?->price ?? 0), currency: $this->order->currency_code) }}
            </dd>
        </div>
    </dl>
</div>
