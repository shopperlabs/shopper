<x-shopper::container class="space-y-8">
    @if ($orders->isNotEmpty())
        @foreach ($orders as $order)
            @php
                $total = $order->total() + (int) $order->shippingOption?->price;
            @endphp

            <div
                class="divide-y divide-gray-200 overflow-hidden rounded-md border border-gray-200 bg-white shadow-sm dark:divide-gray-700 dark:border-gray-700 dark:bg-gray-800"
            >
                <div class="bg-gray-50 px-4 py-3 dark:bg-gray-700 sm:flex sm:items-center sm:justify-between">
                    <div class="flex-1 grow sm:grid sm:grid-cols-4 sm:gap-5">
                        <div>
                            <dt
                                class="text-xs font-medium uppercase leading-4 tracking-wider text-gray-500 dark:text-gray-400"
                            >
                                {{ __('shopper::pages/customers.orders.placed') }}
                            </dt>
                            <dd class="mt-1 text-sm font-medium text-gray-900 dark:text-white">
                                {{ $order->created_at->translatedFormat('j F Y, H:i') }}
                            </dd>
                        </div>
                        <div class="mt-5 sm:mt-0">
                            <dt
                                class="text-xs font-medium uppercase leading-4 tracking-wider text-gray-500 dark:text-gray-400"
                            >
                                {{ __('shopper::pages/customers.orders.total') }}
                            </dt>
                            <dd class="mt-1 text-sm font-medium text-gray-700 dark:text-gray-300">
                                {{ shopper_money_format($total, $order->currency_code) }}
                            </dd>
                        </div>
                        <div class="mt-5 sm:col-span-2 sm:mt-0">
                            <dt
                                class="text-xs font-medium uppercase leading-4 tracking-wider text-gray-500 dark:text-gray-400"
                            >
                                {{ __('shopper::pages/customers.orders.ship_to') }}
                            </dt>
                            <dd class="mt-1 text-sm font-medium text-gray-700 dark:text-gray-300">
                                {{ $order->shippingAddress->street_address }}
                                {{ $order->shippingAddress->postal_code }},
                                {{ $order->shippingAddress->city }}
                                {{ $order->shippingAddress->country_name }}
                            </dd>
                        </div>
                    </div>
                    <div class="mt-2 text-right sm:mt-0 sm:max-w-xs">
                        <dt
                            class="text-xs font-medium uppercase leading-4 tracking-wider text-gray-500 dark:text-gray-400"
                        >
                            {{ __('shopper::pages/customers.orders.details') }}
                        </dt>
                        <dd class="mt-1 text-sm font-medium uppercase text-gray-700 dark:text-gray-300">
                            {{ $order->number }}
                        </dd>
                    </div>
                </div>
                <div class="p-4 sm:grid sm:grid-cols-4 sm:gap-4 sm:px-5 sm:py-6">
                    <div class="sm:col-span-2">
                        <div class="flex items-center space-x-3">
                            <h4 class="font-medium leading-6 text-gray-900 dark:text-white">
                                {{ __('shopper::pages/customers.orders.items') }}
                            </h4>
                            <x-filament::badge
                                size="md"
                                :color="$order->status->getColor()"
                                :icon="$order->status->getIcon()"
                            >
                                {{ $order->status->getLabel() }}
                            </x-filament::badge>
                        </div>
                        <ul class="mt-3 space-y-1">
                            @foreach ($order->items as $item)
                                <li>
                                    <div class="flex items-center gap-2">
                                        <div class="shrink-0">
                                            <img
                                                class="h-8 w-8 rounded-full object-cover"
                                                src="{{ $item->product->getFirstMediaUrl(config('shopper.core.storage.thumbnail_collection')) }}"
                                                alt="{{ $item->name }}"
                                            />
                                        </div>
                                        <p class="text-sm font-medium leading-5 text-gray-500 dark:text-gray-400">
                                            {{ $item->name }} -
                                            <span class="text-gray-700 dark:text-gray-200">
                                                {{ $item->quantity }} x
                                                {{ shopper_money_format($item->unit_price_amount) }}
                                            </span>
                                        </p>
                                    </div>
                                </li>
                            @endforeach
                        </ul>
                    </div>
                    <div class="mt-4 space-y-2 sm:mt-0">
                        <h5 class="text-base font-medium leading-6 text-gray-900 dark:text-white">
                            {{ __('shopper::words.payment_method') }}
                        </h5>
                        @if ($order->paymentMethod)
                            <div class="flex items-center">
                                @if ($order->paymentMethod->logo)
                                    <img
                                        class="h-6 w-6 rounded-md object-cover"
                                        src="{{ $order->paymentMethod->logo_url }}"
                                        alt="payment icon"
                                    />
                                @else
                                    <span
                                        class="inline-flex h-6 w-6 items-center justify-center rounded-md bg-gray-100 text-gray-300 dark:bg-gray-700 dark:text-gray-400"
                                    >
                                        <x-untitledui-image class="h-4 w-4" aria-hidden="true" />
                                    </span>
                                @endif
                                <div class="ml-2">
                                    <p class="text-sm font-medium text-gray-500 dark:text-gray-400">
                                        {{ $order->paymentMethod->title }}
                                    </p>
                                </div>
                            </div>
                        @else
                            <p class="text-sm text-gray-500 dark:text-gray-400">
                                {{ __('shopper::pages/orders.no_payment_method') }}
                            </p>
                        @endif
                    </div>
                    <div class="mt-4 sm:mt-0">
                        <h5 class="text-base font-medium leading-6 text-gray-900 dark:text-white">
                            {{ __('shopper::words.shipping_method') }}
                        </h5>
                        <div class="mt-2">
                            @if ($order->shippingOption)
                                <div class="flex items-center gap-2">
                                    <p class="text-sm text-gray-500 dark:text-gray-400">
                                        {{ $order->shippingOption->name }} -
                                    </p>
                                    <span class="text-sm font-medium text-gray-900 dark:text-white">
                                        {{ shopper_money_format($order->shippingOption->price, $order->currency_code) }}
                                    </span>
                                </div>
                                @if ($order->shippingOption->description)
                                    <p class="mt-0.5 text-sm leading-6 text-gray-500 dark:text-gray-400">
                                        {{ $order->shippingOption->description }}
                                    </p>
                                @endif
                            @else
                                <p class="text-sm font-medium text-gray-500 dark:text-gray-400">
                                    {{ __('shopper::pages/customers.orders.no_shipping') }}
                                </p>
                            @endif
                        </div>
                    </div>
                </div>
                <div class="flex items-center justify-between px-4 py-3">
                    <p class="text-xs font-medium leading-4 text-gray-900 dark:text-white">
                        <span class="mr-3 uppercase tracking-wider text-gray-500 dark:text-gray-400">
                            {{ __('shopper::pages/customers.orders.estimated') }}
                        </span>
                        {{ __('N/A') }}
                    </p>
                    <x-shopper::link
                        href="{{ route('shopper.orders.detail', $order) }}"
                        class="inline-flex items-center text-sm leading-5 text-primary-600 underline hover:text-primary-500"
                    >
                        {{ __('shopper::pages/customers.orders.view') }}
                        <x-untitledui-arrow-narrow-right class="ml-2 h-5 w-5" aria-hidden="true" />
                    </x-shopper::link>
                </div>
            </div>
        @endforeach

        {{ $orders->links() }}
    @else
        <x-shopper::card class="sm:col-span-3">
            <x-shopper::empty-card
                icon="untitledui-shopping-bag"
                :heading="__('shopper::pages/customers.orders.empty_text')"
            />
        </x-shopper::card>
    @endif
</x-shopper::container>
