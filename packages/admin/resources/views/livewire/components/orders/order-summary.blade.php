<div class="divide-sh-border bg-sh-surface ring-sh-border divide-y overflow-hidden rounded-lg ring-1">
    <div class="bg-sh-muted p-3">
        <h4 class="text-sh-fg text-base/5 font-semibold">
            {{ __('shopper::pages/orders.summary') }}
        </h4>
    </div>
    <div class="divide-sh-border divide-y">
        <div class="space-y-4 p-4">
            <div class="flex items-center justify-between">
                <h3 class="text-sh-fg text-sm font-semibold">
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

                            <span class="text-sh-fg font-medium">
                                {{ $order->paymentMethod->title }}
                            </span>
                        </div>
                    @else
                        <span class="text-sh-fg-muted text-sm italic">
                            {{ __('shopper::pages/orders.no_payment_method') }}
                        </span>
                    @endif
                </div>

                <div class="flex items-center justify-between text-sm">
                    <span class="text-sh-fg-muted">
                        {{ __('shopper::words.subtotal') }}
                        <span class="text-xs">
                            ({{
                                $isTaxInclusive
                                    ? __('shopper::pages/settings/taxes.inclusive')
                                    : __('shopper::pages/settings/taxes.exclusive')
                            }})
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
                                {{ $shippingOption->carrier?->name }} &mdash;
                                <span class="text-sh-fg-muted">{{ $shippingOption->name }}</span>
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
            <span class="text-sh-fg text-sm font-semibold">
                {{ __('shopper::words.total') }}
            </span>
            <span class="text-sh-fg text-sm font-semibold">
                {{ shopper_money_format($total, $order->currency_code) }}
            </span>
        </div>

        @if ($refunds->isNotEmpty())
            <div class="space-y-3 p-4">
                @foreach ($refunds as $refund)
                    <div wire:key="refund-{{ $refund->id }}" class="flex items-start justify-between gap-4 text-sm">
                        <div class="min-w-0 space-y-0.5">
                            <div class="flex items-center gap-2">
                                <span class="text-sh-fg">
                                    {{ __('shopper::pages/orders.refund.refunded') }}
                                </span>
                                @unless ($refund->isSuccessful())
                                    <x-filament::badge size="sm" color="warning">
                                        {{ __('shopper::pages/orders.refund.pending') }}
                                    </x-filament::badge>
                                @endunless
                            </div>
                            <p class="text-sh-fg-muted text-xs">
                                {{ $refund->created_at->translatedFormat('j M Y') }}
                                @isset($refund->metadata['reason'])
                                    &middot;
                                    {{ __('shopper::pages/orders.refund.reasons.' . $refund->metadata['reason']) }}
                                @endisset
                            </p>
                        </div>
                        <span class="text-sh-fg tabular-nums">
                            -{{ shopper_money_format($refund->amount, $refund->currency_code) }}
                        </span>
                    </div>
                @endforeach
            </div>

            <div class="flex items-center justify-between p-4">
                <span class="text-sh-fg text-sm font-semibold">
                    {{ __('shopper::pages/orders.refund.net_collected') }}
                </span>
                <span class="text-sh-fg text-sm font-semibold tabular-nums">
                    {{ shopper_money_format($netCollected, $order->currency_code) }}
                </span>
            </div>
        @endif
    </div>
</div>
