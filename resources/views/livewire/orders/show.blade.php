<div x-data="{ open: false }">
    <x:shopper-breadcrumb back="shopper.orders.index">
        <svg class="flex-shrink-0 h-5 w-5 text-gray-400" fill="currentColor" viewBox="0 0 20 20">
            <path fill-rule="evenodd" d="M12.707 5.293a1 1 0 010 1.414L9.414 10l3.293 3.293a1 1 0 01-1.414 1.414l-4-4a1 1 0 010-1.414l4-4a1 1 0 011.414 0z" clip-rule="evenodd"/>
        </svg>
        <a href="{{ route('shopper.orders.index') }}" class="text-gray-500 hover:text-gray-700 focus:outline-none focus:underline transition duration-150 ease-in-out">{{ __('Orders') }}</a>
    </x:shopper-breadcrumb>

    <div class="mt-3 bg-gray-100 z-30 relative pb-5 border-b border-gray-200 sticky top-4 sm:top-0 sm:-mx-8">
        <div class="sm:px-8 space-y-4">
            <div class="space-y-3 md:flex md:items-center md:justify-between md:space-y-0">
                <div class="flex-1 flex items-center space-x-4 min-w-0">
                    <h3 class="text-2xl font-bold leading-6 text-gray-900 sm:text-3xl sm:leading-9 sm:truncate">{{ $order->number }}</h3>
                    <div class="p-1 flex items-center divide-x-2 divide-gray-200">
                        <div class="flex items-center space-x-2 pr-4">
                            <span class="inline-flex items-center px-2.5 py-0.5 border-2 rounded-full text-xs font-medium {{ $order->status_classes }}">
                                {{ $order->formatted_status }}
                            </span>
                        </div>
                        <div class="flex items-center px-4">
                            <svg class="h-5 w-5 mr-1.5 text-gray-500" fill="currentColor" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg">
                                <path fill-rule="evenodd" d="M6 2a1 1 0 00-1 1v1H4a2 2 0 00-2 2v10a2 2 0 002 2h12a2 2 0 002-2V6a2 2 0 00-2-2h-1V3a1 1 0 10-2 0v1H7V3a1 1 0 00-1-1zm0 5a1 1 0 000 2h8a1 1 0 100-2H6z" clip-rule="evenodd" />
                            </svg>
                            <span class="text-gray-600 font-medium text-sm leading-6">
                                <time datetime="{{ $order->created_at->format('F j, Y h:m') }}">
                                    {{ $order->created_at->formatLocalized('%d %B %G - %H:%M') }}
                                </time>
                            </span>
                        </div>
                    </div>
                </div>
                <div class="flex space-x-3">
                    <span class="hidden sm:block">
                        <x-shopper-danger-button wire:click="openModale" type="button">
                            <svg class="w-5 h-5 -ml-1 mr-2" x-description="Heroicon name: archive" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor" aria-hidden="true">
                                <path d="M4 3a2 2 0 100 4h12a2 2 0 100-4H4z" />
                                <path fill-rule="evenodd" d="M3 8h14v7a2 2 0 01-2 2H5a2 2 0 01-2-2V8zm5 3a1 1 0 011-1h2a1 1 0 110 2H9a1 1 0 01-1-1z" clip-rule="evenodd" />
                            </svg>
                            {{ __("Archived") }}
                        </x-shopper-danger-button>
                    </span>

                    <div @keydown.escape="open = false" @click.away="open = false" class="relative block text-left">
                        <x-shopper-default-button @click="open = !open" type="button">
                            {{ __("More actions") }}
                            <svg class="w-5 h-5 -mr-1 ml-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 9l4-4 4 4m0 6l-4 4-4-4" />
                            </svg>
                        </x-shopper-default-button>
                        <div x-show="open" x-description="Dropdown panel, show/hide based on dropdown state." x-transition:enter="transition ease-out duration-100" x-transition:enter-start="transform opacity-0 scale-95" x-transition:enter-end="transform opacity-100 scale-100" x-transition:leave="transition ease-in duration-75" x-transition:leave-start="transform opacity-100 scale-100" x-transition:leave-end="transform opacity-0 scale-95" class="origin-top-right absolute right-0 mt-2 w-56 rounded-md shadow-lg bg-white ring-1 ring-black ring-opacity-5 divide-y divide-gray-100" role="menu" aria-orientation="vertical" aria-labelledby="options-menu" style="display: none;">
                            <div class="py-1">
                                <button type="button" class="group w-full flex items-center px-4 py-2 text-sm text-gray-700 hover:bg-gray-100 hover:text-gray-900" role="menuitem">
                                    <svg class="mr-3 h-5 w-5 text-gray-400 group-hover:text-gray-500" x-description="Heroicon name: duplicate" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor" aria-hidden="true">
                                        <path d="M7 9a2 2 0 012-2h6a2 2 0 012 2v6a2 2 0 01-2 2H9a2 2 0 01-2-2V9z" />
                                        <path d="M5 3a2 2 0 00-2 2v6a2 2 0 002 2V5h8a2 2 0 00-2-2H5z" />
                                    </svg>
                                    {{ __("Duplicate") }}
                                </button>
                                @if($order->canBeCancelled())
                                    <button type="button" class="group w-full flex items-center px-4 py-2 text-sm text-gray-700 hover:bg-gray-100 hover:text-gray-900" role="menuitem">
                                        <svg class="mr-3 h-5 w-5 text-gray-400 group-hover:text-gray-500" fill="currentColor" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg">
                                            <path fill-rule="evenodd" d="M4.293 4.293a1 1 0 011.414 0L10 8.586l4.293-4.293a1 1 0 111.414 1.414L11.414 10l4.293 4.293a1 1 0 01-1.414 1.414L10 11.414l-4.293 4.293a1 1 0 01-1.414-1.414L8.586 10 4.293 5.707a1 1 0 010-1.414z" clip-rule="evenodd" />
                                        </svg>
                                        {{ __("Cancel Order") }}
                                    </button>
                                @endif
                            </div>
                        </div>
                    </div>

                    <span class="relative z-0 inline-flex shadow-sm">
                        <button @if($prevOrder) wire:click="goToOrder({{ $prevOrder->id }})" @endif type="button" class="relative inline-flex items-center px-2 py-2 rounded-l-md border border-gray-300 bg-white text-sm leading-5 font-medium text-gray-500 hover:text-gray-400 focus:z-10 focus:outline-none focus:border-blue-300 focus:shadow-outline-blue active:bg-gray-100 active:text-gray-500 transition ease-in-out duration-150 @if(! $prevOrder) bg-gray-100 hover:text-gray-500 disabled:opacity-50 @endif" aria-label="{{ __('Previous order') }}" @if(! $prevOrder) disabled @endif>
                            <svg class="h-5 w-5" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor">
                                <path fill-rule="evenodd" d="M12.707 5.293a1 1 0 010 1.414L9.414 10l3.293 3.293a1 1 0 01-1.414 1.414l-4-4a1 1 0 010-1.414l4-4a1 1 0 011.414 0z" clip-rule="evenodd" />
                            </svg>
                        </button>
                        <button @if($nextOrder) wire:click="goToOrder({{ $nextOrder->id }})" @endif type="button" class="-ml-px relative inline-flex items-center px-2 py-2 rounded-r-md border border-gray-300 bg-white text-sm leading-5 font-medium text-gray-500 hover:text-gray-400 focus:z-10 focus:outline-none focus:border-blue-300 focus:shadow-outline-blue active:bg-gray-100 active:text-gray-500 transition ease-in-out duration-150 @if(! $nextOrder) bg-gray-100 hover:text-gray-500 disabled:opacity-50 @endif" aria-label="{{ __('Next order') }}" @if(! $nextOrder) disabled @endif>
                            <svg class="h-5 w-5" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor">
                                <path fill-rule="evenodd" d="M7.293 14.707a1 1 0 010-1.414L10.586 10 7.293 6.707a1 1 0 011.414-1.414l4 4a1 1 0 010 1.414l-4 4a1 1 0 01-1.414 0z" clip-rule="evenodd" />
                            </svg>
                        </button>
                    </span>

                </div>
            </div>
        </div>
    </div>

    <div class="grid gap-6 sm:grid-cols-6 sm:gap-8">
        <div class="sm:col-span-4 py-2 divide-y divide-gray-200">
            <div class="py-4">
                <h3 class="text-lg leading-6 font-medium text-gray-900">{{ __('Order items') }}</h3>
            </div>
        </div>
        <div class="sm:col-span-2 border-t py-1 sm:border-t-0 sm:border-l border-gray-200 sm:pl-8 divide-y divide-gray-200">
            <div class="py-4">
                <h3 class="text-lg leading-6 font-medium text-gray-900">{{ __('Customer') }}</h3>
                <div class="mt-4 flex items-center space-x-4">
                    <div class="flex-shrink-0">
                        <img class="h-8 w-8 rounded-full" src="{{ $order->customer->picture }}" alt="Customer profile">
                    </div>
                    <div class="flex-1 min-w-0">
                        <p class="text-sm font-medium text-gray-900 truncate">{{ $order->customer->full_name }}</p>
                    </div>
                    <div>
                        <a href="{{ route('shopper.customers.show', $order->customer) }}" class="inline-flex items-center shadow-sm px-2.5 py-0.5 border border-gray-300 text-sm leading-5 font-medium rounded-full text-gray-700 bg-white hover:bg-gray-50">
                            {{ __('View') }}
                        </a>
                    </div>
                </div>
                <div class="mt-3">
                    <p class="text-sm text-gray-500 leading-5">
                        {{ __("Customer for :date", ['date' => $order->customer->created_at->diffForHumans()]) }}
                    </p>
                    <p class="text-sm text-gray-500 leading-5">
                        {{ __('This customer has already placed :number order(s)', ['number' => $order->customer->orders->count()]) }}
                    </p>
                </div>
            </div>
            <div class="py-4">
                <h3 class="text-xs uppercase tracking-wider font-medium leading-4 text-gray-900">{{ __('Contact Information') }}</h3>
                <div class="mt-3 space-y-1">
                    <p class="text-sm leading-5 text-gray-500">
                        <a href="mailto:{{ $order->customer->email }}" class="underline text-blue-600 hover:text-blue-500">{{ $order->customer->email }}</a>
                    </p>
                    <p class="text-sm leading-5 text-gray-500">{{ $order->customer->phone_number ?? __('No phone number') }}</p>
                </div>
            </div>
            <div class="py-4">
                <h3 class="text-xs uppercase tracking-wider font-medium leading-4 text-gray-900">{{ __('Shipping Address') }}</h3>
                <p class="mt-3 text-sm text-gray-500 leading-5">
                    {{ $order->shippingAddress->full_name }}<br>
                    @if($order->shippingAddress->company_name)
                        {{ $order->shippingAddress->company_name }}<br>
                    @endif
                    {{ $order->shippingAddress->street_address }}<br>
                    {{ $order->shippingAddress->zipcode }} {{ $order->shippingAddress->city }}<br>
                    {{ $order->shippingAddress->country->name }}<br>
                    @if($order->shippingAddress->phone_number)
                        <span>{{ $order->shippingAddress->phone_number }}</span>
                    @endif
                </p>
            </div>
            <div class="py-4">
                <h3 class="text-xs uppercase tracking-wider font-medium leading-4 text-gray-900">{{ __('Billing Address') }}</h3>
                @if(! $billingAddress || ($billingAddress && $billingAddress->id === $order->shippingAddress->id ))
                    <p class="mt-3 text-gray-500 text-sm leading-5">{{ __('Same as shipping address') }}</p>
                @else
                    <p class="mt-3 text-sm text-gray-500 leading-5">
                        {{ $billingAddress->full_name }}<br>
                        @if($billingAddress->company_name)
                            {{ $billingAddress->company_name }}<br>
                        @endif
                        {{ $billingAddress->street_address }}<br>
                        {{ $billingAddress->zipcode }} {{ $billingAddress->city }}<br>
                        {{ $billingAddress->country->name }}<br>
                        @if($billingAddress->phone_number)
                            <span>{{ $billingAddress->phone_number }}</span>
                        @endif
                    </p>
                @endif
            </div>
        </div>
    </div>
</div>
