<x-shopper::container class="space-y-8">
    @forelse ($orders as $order)
        <div
            class="divide-y divide-gray-200 overflow-hidden rounded-md border border-gray-200 bg-white shadow-sm dark:divide-gray-700 dark:border-gray-700 dark:bg-gray-800"
        >
            <div class="bg-gray-100 px-4 py-3 dark:bg-gray-700 sm:flex sm:items-center sm:justify-between">
                <div class="flex-1 grow sm:grid sm:grid-cols-4 sm:gap-5">
                    <div>
                        <dt
                            class="text-xs font-medium uppercase leading-4 tracking-wider text-gray-500 dark:text-gray-400"
                        >
                            {{ __('shopper::pages/customers.orders.placed') }}
                        </dt>
                        <dd class="mt-1 text-sm font-medium text-gray-900 dark:text-white">
                            {{ $order->created_at->formatLocalized('%d %B %G - %H:%M') }}
                        </dd>
                    </div>
                    <div class="mt-5 sm:mt-0">
                        <dt
                            class="text-xs font-medium uppercase leading-4 tracking-wider text-gray-500 dark:text-gray-400"
                        >
                            {{ __('shopper::pages/customers.orders.total') }}
                        </dt>
                        <dd class="mt-1 text-sm font-medium text-gray-900 dark:text-white">
                            {{ $order->total }}
                        </dd>
                    </div>
                    <div class="mt-5 sm:col-span-2 sm:mt-0">
                        <dt
                            class="text-xs font-medium uppercase leading-4 tracking-wider text-gray-500 dark:text-gray-400"
                        >
                            {{ __('shopper::pages/customers.orders.ship_to') }}
                        </dt>
                        <dd class="mt-1 text-sm font-medium text-gray-900 dark:text-white">
                            {{ $order->shippingAddress->street_address }}
                            {{ $order->shippingAddress->zipcode ?? '' }}, {{ $order->shippingAddress->country->name }}
                        </dd>
                    </div>
                </div>
                <div class="mt-2 text-right sm:mt-0 sm:max-w-xs">
                    <dt class="text-xs font-medium uppercase leading-4 tracking-wider text-gray-500 dark:text-gray-400">
                        {{ __('shopper::pages/customers.orders.order_number', ['number' => $order->number]) }}
                    </dt>
                    <dd class="mt-1 text-sm font-medium text-gray-900 dark:text-white">
                        {{ __('shopper::pages/customers.orders.details') }}
                    </dd>
                </div>
            </div>
            <div class="p-4 sm:grid sm:grid-cols-4 sm:gap-4 sm:px-5 sm:py-6">
                <div class="sm:col-span-2">
                    <div class="flex items-center space-x-3">
                        <h4 class="text-lg font-bold leading-6 text-gray-900 dark:text-white">
                            {{ __('shopper::pages/customers.orders.items') }}
                        </h4>
                        <span
                            class="{{ $order->status_classes }} inline-flex items-center rounded-full px-2.5 py-0.5 text-xs font-medium"
                        >
                            {{ $order->formatted_status }}
                        </span>
                    </div>
                    <ul class="mt-3 list-disc space-y-1 pl-4">
                        @foreach ($order->items as $item)
                            <li class="text-sm font-medium leading-5 text-gray-500 dark:text-gray-400">
                                {{ $item->name }} -
                                <span class="text-gray-800 dark:text-gray-200">
                                    {{ shopper_money_format($item->unit_price_amount) }} x {{ $item->quantity }}
                                </span>
                            </li>
                        @endforeach
                    </ul>
                </div>
                <div class="mt-4 sm:mt-0">
                    <h5 class="text-base font-medium leading-6 text-gray-900 dark:text-white">
                        {{ __('shopper::words.payment_method') }}
                    </h5>
                    <div class="mt-2 flex items-center">
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
                </div>
                <div class="mt-4 sm:mt-0">
                    <h5 class="text-base font-medium leading-6 text-gray-900 dark:text-white">
                        {{ __('shopper::words.shipping_method') }}
                    </h5>
                    <div class="mt-2 flex items-center">
                        @if ($order->shipping_method)
                            <p class="text-sm font-medium text-gray-500 dark:text-gray-400">
                                {{ $order->shipping_method }}
                            </p>
                            <span class="ml-1 text-sm text-gray-400 dark:text-gray-500">
                                {{ shopper_money_format($order->shipping_total) }}
                            </span>
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
                <a
                    href="{{ route('shopper.orders.show', $order) }}"
                    class="inline-flex items-center text-sm leading-5 text-primary-600 underline hover:text-primary-500"
                >
                    {{ __('shopper::pages/customers.orders.view') }}
                    <x-untitledui-arrow-narrow-right class="ml-2 h-5 w-5" aria-hidden="true" />
                </a>
            </div>
        </div>
    @empty
        <x-shopper::card class="sm:col-span-3">
            <x-shopper::empty-card
                icon="untitledui-shopping-bag"
                :heading="__('shopper::pages/customers.orders.empty_text')"
            />
        </x-shopper::card>
    @endforelse
</x-shopper::container>
