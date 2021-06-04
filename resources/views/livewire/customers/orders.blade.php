<div class="space-y-8">
    @forelse($orders as $order)
        <div class="bg-white border border-gray-200 rounded-md shadow-sm overflow-hidden divide-y divide-gray-200 dark:bg-gray-800 dark:border-gray-700 dark:divide-gray-700">
            <div class="bg-gray-100 py-3 px-4 sm:flex sm:items-center sm:justify-between">
                <div class="flex-1 flex-grow sm:grid sm:grid-cols-4 sm:gap-5">
                    <div>
                        <dt class="text-xs leading-4 uppercase tracking-wider font-medium text-gray-500 dark:text-gray-400">
                            {{ __('Order Placed') }}
                        </dt>
                        <dd class="mt-1 text-sm font-medium text-gray-900 dark:text-white">
                            {{ $order->created_at->formatLocalized('%d %B %G - %H:%M') }}
                        </dd>
                    </div>
                    <div class="mt-5 sm:mt-0">
                        <dt class="text-xs leading-4 uppercase tracking-wider font-medium text-gray-500 dark:text-gray-400">
                            {{ __('Total') }}
                        </dt>
                        <dd class="mt-1 text-sm font-medium text-gray-900 dark:text-white">
                            {{ $order->total }}
                        </dd>
                    </div>
                    <div class="sm:col-span-2 mt-5 sm:mt-0">
                        <dt class="text-xs leading-4 uppercase tracking-wider font-medium text-gray-500 dark:text-gray-400">
                            {{ __('Ship To') }}
                        </dt>
                        <dd class="mt-1 text-sm font-medium text-gray-900 dark:text-white">
                            {{ $order->shippingAddress->street_address }}, {{ $order->shippingAddress->country->name }}
                        </dd>
                    </div>
                </div>
                <div class="mt-2 sm:mt-0 text-right sm:max-w-xs">
                    <dt class="text-xs leading-4 uppercase tracking-wider font-medium text-gray-500 dark:text-gray-400">
                        {{ __('Order :number', ['number' => $order->number]) }}
                    </dt>
                    <dd class="mt-1 text-sm font-medium text-gray-900 dark:text-white">
                        {{ __('Order Details') }}
                    </dd>
                </div>
            </div>
            <div class="p-4 sm:px-5 sm:py-6 sm:grid sm:grid-cols-4 sm:gap-4">
                <div class="sm:col-span-2">
                    <div class="flex items-center space-x-3">
                        <h4 class="text-lg font-bold text-gray-900 leading-6 dark:text-white">{{ __('Order items') }}</h4>
                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium {{ $order->status_classes }}">
                            {{ $order->formatted_status }}
                        </span>
                    </div>
                    <ul class="mt-3 pl-4 list-disc space-y-1">
                        @foreach($order->items as $item)
                            <li class="text-gray-500 text-sm leading-5 font-medium dark:text-gray-400">
                                {{ $item->name }} -
                                <span class="text-gray-800 dark:text-gray-200">{{ shopper_money_format($item->unit_price_amount) }} x {{ $item->quantity }}</span>
                            </li>
                        @endforeach
                    </ul>
                </div>
                <div class="mt-4 sm:mt-0">
                    <h5 class="text-base leading-6 font-medium text-gray-900 dark:text-white">{{ __('Payment method') }}</h5>
                    <div class="mt-2 flex items-center">
                        @if($order->paymentMethod->logo)
                            <img class="h-6 w-6 rounded-md object-cover" src="{{ $order->paymentMethod->logo_url }}" alt="payment icon" />
                        @else
                            <span class="flex items-center justify-center h-6 w-6 bg-gray-100 text-gray-300 rounded-md dark:bg-gray-700 dark:text-gray-400">
                                <svg fill="none" stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" viewBox="0 0 24 24" class="w-6 h-6">
                                    <path d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z" />
                                </svg>
                            </span>
                        @endif
                        <div class="ml-2">
                            <p class="text-sm font-medium text-gray-500 dark:text-gray-400">{{ $order->paymentMethod->title }}</p>
                        </div>
                    </div>
                </div>
                <div class="mt-4 sm:mt-0">
                    <h5 class="text-base leading-6 font-medium text-gray-900 dark:text-white">{{ __('Shipping method') }}</h5>
                    <div class="mt-2 flex items-center">
                        @if($order->shipping_method)
                            <p class="text-sm font-medium text-gray-500 dark:text-gray-400">{{ $order->shipping_method }}</p>
                            <span class="ml-1 text-sm text-gray-400 dark:text-gray-500">{{ shopper_money_format($order->shipping_total) }}</span>
                        @else
                            <p class="text-sm font-medium text-gray-500 dark:text-gray-400">{{ __('No shipping method.') }}</p>
                        @endif
                    </div>
                </div>
            </div>
            <div class="py-3 px-4 flex items-center justify-between">
                <p class="text-xs text-gray-900 font-medium leading-4">
                    <span class="uppercase tracking-wider mr-3 text-gray-500 dark:text-gray-400">
                        {{ __('Estimated Delivery:') }}
                    </span>
                    {{ __('No Assigned.') }}
                </p>
                <a class="text-blue-600 hover:text-blue-500 underline text-sm leading-5 dark:text-blue-400" href="{{ route('shopper.orders.show', $order) }}">
                    {{ __('View order') }}
                </a>
            </div>
        </div>
    @empty
        <div class="bg-white rounded-lg shadow-md overflow-hidden py-10 sm:py-12 flex items-center justify-center dark:bg-gray-800">
            <div class="flex justify-center items-center space-x-2">
                <x-heroicon-o-shopping-bag class="h-8 w-8 text-gray-400" />
                <span class="font-medium py-8 text-gray-400 text-xl">{{ __('No orders found') }}...</span>
            </div>
        </div>
    @endforelse

    {{ $orders->links() }}
</div>
