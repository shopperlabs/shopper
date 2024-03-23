<x-shopper::container class="space-y-8">
    @forelse($orders as $order)
        <div class="bg-white border border-secondary-200 rounded-md shadow-sm overflow-hidden divide-y divide-secondary-200 dark:bg-secondary-800 dark:border-secondary-700 dark:divide-secondary-700">
            <div class="bg-secondary-100 dark:bg-secondary-700 py-3 px-4 sm:flex sm:items-center sm:justify-between">
                <div class="flex-1 grow sm:grid sm:grid-cols-4 sm:gap-5">
                    <div>
                        <dt class="text-xs leading-4 uppercase tracking-wider font-medium text-secondary-500 dark:text-secondary-400">
                            {{ __('shopper::pages/customers.orders.placed') }}
                        </dt>
                        <dd class="mt-1 text-sm font-medium text-secondary-900 dark:text-white">
                            {{ $order->created_at->formatLocalized('%d %B %G - %H:%M') }}
                        </dd>
                    </div>
                    <div class="mt-5 sm:mt-0">
                        <dt class="text-xs leading-4 uppercase tracking-wider font-medium text-secondary-500 dark:text-secondary-400">
                            {{ __('shopper::pages/customers.orders.total') }}
                        </dt>
                        <dd class="mt-1 text-sm font-medium text-secondary-900 dark:text-white">
                            {{ $order->total }}
                        </dd>
                    </div>
                    <div class="sm:col-span-2 mt-5 sm:mt-0">
                        <dt class="text-xs leading-4 uppercase tracking-wider font-medium text-secondary-500 dark:text-secondary-400">
                            {{ __('shopper::pages/customers.orders.ship_to') }}
                        </dt>
                        <dd class="mt-1 text-sm font-medium text-secondary-900 dark:text-white">
                            {{ $order->shippingAddress->street_address }} {{ $order->shippingAddress->zipcode ?? '' }}, {{ $order->shippingAddress->country->name }}
                        </dd>
                    </div>
                </div>
                <div class="mt-2 sm:mt-0 text-right sm:max-w-xs">
                    <dt class="text-xs leading-4 uppercase tracking-wider font-medium text-secondary-500 dark:text-secondary-400">
                        {{ __('shopper::pages/customers.orders.order_number', ['number' => $order->number]) }}
                    </dt>
                    <dd class="mt-1 text-sm font-medium text-secondary-900 dark:text-white">
                        {{ __('shopper::pages/customers.orders.details') }}
                    </dd>
                </div>
            </div>
            <div class="p-4 sm:px-5 sm:py-6 sm:grid sm:grid-cols-4 sm:gap-4">
                <div class="sm:col-span-2">
                    <div class="flex items-center space-x-3">
                        <h4 class="text-lg font-bold text-secondary-900 leading-6 dark:text-white">
                            {{ __('shopper::pages/customers.orders.items') }}
                        </h4>
                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium {{ $order->status_classes }}">
                            {{ $order->formatted_status }}
                        </span>
                    </div>
                    <ul class="mt-3 pl-4 list-disc space-y-1">
                        @foreach($order->items as $item)
                            <li class="text-secondary-500 text-sm leading-5 font-medium dark:text-secondary-400">
                                {{ $item->name }} -
                                <span class="text-secondary-800 dark:text-secondary-200">{{ shopper_money_format($item->unit_price_amount) }} x {{ $item->quantity }}</span>
                            </li>
                        @endforeach
                    </ul>
                </div>
                <div class="mt-4 sm:mt-0">
                    <h5 class="text-base leading-6 font-medium text-secondary-900 dark:text-white">
                        {{ __('shopper::words.payment_method') }}
                    </h5>
                    <div class="mt-2 flex items-center">
                        @if($order->paymentMethod->logo)
                            <img class="h-6 w-6 rounded-md object-cover" src="{{ $order->paymentMethod->logo_url }}" alt="payment icon" />
                        @else
                            <span class="inline-flex items-center justify-center h-6 w-6 bg-secondary-100 text-secondary-300 rounded-md dark:bg-secondary-700 dark:text-secondary-400">
                                <x-untitledui-image class="h-4 w-4" />
                            </span>
                        @endif
                        <div class="ml-2">
                            <p class="text-sm font-medium text-secondary-500 dark:text-secondary-400">
                                {{ $order->paymentMethod->title }}
                            </p>
                        </div>
                    </div>
                </div>
                <div class="mt-4 sm:mt-0">
                    <h5 class="text-base leading-6 font-medium text-secondary-900 dark:text-white">
                        {{ __('shopper::words.shipping_method') }}
                    </h5>
                    <div class="mt-2 flex items-center">
                        @if($order->shipping_method)
                            <p class="text-sm font-medium text-secondary-500 dark:text-secondary-400">
                                {{ $order->shipping_method }}
                            </p>
                            <span class="ml-1 text-sm text-secondary-400 dark:text-secondary-500">
                                {{ shopper_money_format($order->shipping_total) }}
                            </span>
                        @else
                            <p class="text-sm font-medium text-secondary-500 dark:text-secondary-400">
                                {{ __('shopper::pages/customers.orders.no_shipping') }}
                            </p>
                        @endif
                    </div>
                </div>
            </div>
            <div class="py-3 px-4 flex items-center justify-between">
                <p class="text-xs text-secondary-900 font-medium leading-4 dark:text-white">
                    <span class="uppercase tracking-wider mr-3 text-secondary-500 dark:text-secondary-400">
                        {{ __('shopper::pages/customers.orders.estimated') }}
                    </span>
                    {{ __('N/A') }}
                </p>
                <a href="{{ route('shopper.orders.show', $order) }}" class="inline-flex items-center text-primary-600 hover:text-primary-500 underline text-sm leading-5">
                    {{ __('shopper::pages/customers.orders.view') }}
                    <x-untitledui-arrow-narrow-right class="h-5 w-5 ml-2" />
                </a>
            </div>
        </div>
    @empty
        <div class="py-6 flex items-center justify-center dark:bg-secondary-800">
            <div class="flex flex-col justify-center items-center space-y-2">
                <x-untitledui-shopping-bag class="h-8 w-8 text-primary-500" />
                <span class="font-medium text-secondary-500 dark:text-secondary-400 text-xl">
                    {{ __('shopper::pages/customers.orders.empty_text') }}
                </span>
            </div>
        </div>
    @endforelse

    <div class="flex items-center justify-between">
        {{ $orders->links() }}
    </div>
</x-shopper::container>
