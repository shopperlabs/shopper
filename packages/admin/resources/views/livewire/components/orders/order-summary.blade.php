<div class="overflow-hidden rounded-lg divide-y divide-sh-border bg-sh-surface ring-1 ring-sh-border">
    <div class="p-3 bg-sh-muted">
        <h4 class="text-base/5 text-sh-fg font-semibold">
            {{ __('shopper::pages/orders.summary') }}
        </h4>
    </div>
    <div class="divide-y divide-sh-border">
        <div class="space-y-4 p-4">
            <div class="flex items-center justify-between">
                <h3 class="text-sm font-semibold text-sh-fg">
                    {{ __('shopper::pages/orders.payment_details') }}
                </h3>
                <x-filament::badge
                    size="sm"
                    :color="$order->payment_status->getColor()"
                    :icon="$order->payment_status->getIcon()"
                >
                    {{ $order->payment_status->getLabel() }}
                </x-filament::badge>
            </div>

            <div class="space-y-3">
                <div class="flex items-start justify-between text-sm">
                    <span class="text-sh-fg-muted">
                        {{ __('shopper::words.payment_method') }}
                    </span>
                    @if ($order->paymentMethod)
                        <div class="flex items-center gap-2">
                            @if ($paymentLogoUrl)
                                <img
                                    class="h-6 w-auto rounded object-contain"
                                    src="{{ $paymentLogoUrl }}"
                                    alt="{{ $order->paymentMethod->title }}"
                                />
                            @endif
                            <span class="font-medium text-sh-fg">
                                {{ $order->paymentMethod->title }}
                            </span>
                        </div>
                    @else
                        <span class="text-sm text-sh-fg-muted italic">
                            {{ __('shopper::pages/orders.no_payment_method') }}
                        </span>
                    @endif
                </div>

                <div class="flex items-center justify-between text-sm">
                    <span class="text-sh-fg-muted">
                        {{ __('shopper::words.subtotal') }}
                        <span class="text-xs">
                            ({{ $isTaxInclusive
                                ? __('shopper::pages/settings/taxes.inclusive')
                                : __('shopper::pages/settings/taxes.exclusive') }})
                        </span>
                    </span>
                    <span class="text-sh-fg">
                        {{ trans_choice('shopper::words.item_count', $itemsCount, ['count' => $itemsCount]) }}
                        &middot;
                        {{ shopper_money_format($subtotal, $order->currency_code) }}
                    </span>
                </div>

                @if ($shippingOption)
                    <div class="flex items-start justify-between text-sm">
                        <span class="text-sh-fg-muted">
                            {{ __('shopper::pages/orders.shipping_type') }}
                        </span>
                        <div class="flex items-center gap-2">
                            @if ($carrierLogoUrl)
                                <img
                                    class="h-6 w-auto rounded object-contain"
                                    src="{{ $carrierLogoUrl }}"
                                    alt="{{ $shippingOption->carrier?->name }}"
                                />
                            @endif
                            <span class="text-sh-fg">
                                {{ $shippingOption->carrier?->name }} &mdash; <span class="text-sh-fg-muted">{{ $shippingOption->name }}</span>
                            </span>
                        </div>
                    </div>
                @endif

                <div class="flex items-center justify-between text-sm">
                    <span class="text-sh-fg-muted">
                        {{ __('shopper::pages/orders.shipping_fee') }}
                    </span>
                    <span class="text-sh-fg">
                        {{ shopper_money_format($shippingPrice, $order->currency_code) }}
                    </span>
                </div>

                <div class="flex items-center justify-between text-sm">
                    <span class="text-sh-fg-muted">
                        {{ __('shopper::pages/orders.tax') }}
                    </span>
                    @if ($taxAmount > 0)
                        <span class="text-sh-fg">
                            {{ shopper_money_format($taxAmount, $order->currency_code) }}
                        </span>
                    @else
                        <x-filament::badge color="gray">
                            {{ __('shopper::words.not_available') }}
                        </x-filament::badge>
                    @endif
                </div>
            </div>
        </div>

        <div class="flex items-center justify-between p-4">
            <span class="text-sm font-semibold text-sh-fg">
                {{ __('shopper::words.total') }}
            </span>
            <span class="text-sm font-semibold text-sh-fg">
                {{ shopper_money_format($total, $order->currency_code) }}
            </span>
        </div>
    </div>
</div>
