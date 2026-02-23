<div class="overflow-hidden rounded-lg divide-y divide-gray-200 bg-white ring-1 ring-gray-200 dark:bg-gray-900 dark:ring-white/10 dark:divide-white/10">
    <div class="p-3 bg-gray-50 dark:bg-gray-950">
        <h4 class="text-base/5 text-gray-900 font-semibold dark:text-white">
            {{ __('shopper::pages/orders.summary') }}
        </h4>
    </div>
    <div class="divide-y divide-gray-200 dark:divide-white/10">
        <div class="space-y-4 p-4">
            <div class="flex items-center justify-between">
                <h3 class="text-sm font-semibold text-gray-900 dark:text-white">
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
                    <span class="text-gray-500 dark:text-gray-400">
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
                            <span class="font-medium text-gray-900 dark:text-white">
                                {{ $order->paymentMethod->title }}
                            </span>
                        </div>
                    @else
                        <span class="text-sm text-gray-400 italic dark:text-gray-500">
                            {{ __('shopper::pages/orders.no_payment_method') }}
                        </span>
                    @endif
                </div>

                <div class="flex items-center justify-between text-sm">
                    <span class="text-gray-500 dark:text-gray-400">
                        {{ __('shopper::words.subtotal') }}
                    </span>
                    <span class="text-gray-900 dark:text-white">
                        {{ trans_choice('shopper::words.item_count', $itemsCount, ['count' => $itemsCount]) }}
                        &middot;
                        {{ shopper_money_format($subtotal, $order->currency_code) }}
                    </span>
                </div>

                @if ($shippingOption)
                    <div class="flex items-start justify-between text-sm">
                        <span class="text-gray-500 dark:text-gray-400">
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
                            <span class="text-gray-900 dark:text-white">
                                {{ $shippingOption->carrier?->name }} &mdash; <span class="text-gray-500 dark:text-gray-400">{{ $shippingOption->name }}</span>
                            </span>
                        </div>
                    </div>
                @endif

                <div class="flex items-center justify-between text-sm">
                    <span class="text-gray-500 dark:text-gray-400">
                        {{ __('shopper::pages/orders.shipping_fee') }}
                    </span>
                    <span class="text-gray-900 dark:text-white">
                        {{ shopper_money_format($shippingPrice, $order->currency_code) }}
                    </span>
                </div>

                <div class="flex items-center justify-between text-sm">
                    <span class="text-gray-500 dark:text-gray-400">
                        {{ __('shopper::pages/orders.shipping_tax') }}
                    </span>
                    <x-filament::badge color="gray">
                        {{ __('shopper::words.not_available') }}
                    </x-filament::badge>
                </div>
            </div>
        </div>

        <div class="flex items-center justify-between p-4">
            <span class="text-sm font-semibold text-gray-900 dark:text-white">
                {{ __('shopper::words.total') }}
            </span>
            <span class="text-sm font-semibold text-gray-900 dark:text-white">
                {{ shopper_money_format($total, $order->currency_code) }}
            </span>
        </div>
    </div>
</div>
