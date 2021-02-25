<div x-data="{ open: false }">
    <x:shopper-breadcrumb back="shopper.orders.index">
        <svg class="flex-shrink-0 h-5 w-5 text-gray-400" fill="currentColor" viewBox="0 0 20 20">
            <path fill-rule="evenodd" d="M12.707 5.293a1 1 0 010 1.414L9.414 10l3.293 3.293a1 1 0 01-1.414 1.414l-4-4a1 1 0 010-1.414l4-4a1 1 0 011.414 0z" clip-rule="evenodd"/>
        </svg>
        <a href="{{ route('shopper.orders.index') }}" class="text-gray-500 hover:text-gray-700 focus:outline-none focus:underline transition duration-150 ease-in-out">{{ __('Orders') }}</a>
    </x:shopper-breadcrumb>

    <div class="mt-3 bg-gray-100 z-30 relative pb-5 border-b border-gray-200 sticky top-0 -my-2 pt-4 sm:pt-0 sm:-my-0 sm:-mx-8">
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

    <div class="grid sm:grid-cols-6">
        <div class="sm:col-span-4 py-2 divide-y divide-gray-200">
            <div class="py-4 sm:pr-8">
                <div class="flex items-center justify-between">
                    <h3 class="text-lg leading-6 font-medium text-gray-900">{{ __('Products') }}</h3>
                    <div class="flex items-center space-x-3">
                        <span class="whitespace-no-wrap text-sm font-medium text-gray-500">{{ __('Per Page') }}</span>
                        <x-shopper-input.select wire:model="perPage" class="w-20" aria-label="{{ __('Per Page items') }}">
                            <option value="3">3</option>
                            <option value="5">5</option>
                            <option value="10">10</option>
                        </x-shopper-input.select>
                    </div>
                </div>
                <div class="mt-4">
                    <ul class="divide-y divide-gray-200">
                        @foreach($items as $item)
                            <li class="flex-1 py-2.5">
                                <div class="flex items-center">
                                    <div class="min-w-0 flex-1 flex items-center">
                                        <div class="flex-shrink-0">
                                            <div class="flex-shrink-0 h-10 w-10">
                                                @if($item->product->getFirstImage())
                                                    <img class="h-10 w-10 rounded-md object-cover" src="{{ $item->product->getFirstImage()->file_path }}" alt="{{ $item->id }}" />
                                                @else
                                                    <span class="flex items-center justify-center h-10 w-10 bg-gray-100 text-gray-300 rounded-md">
                                                        <svg fill="none" stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" viewBox="0 0 24 24" class="w-6 h-6">
                                                            <path d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z" />
                                                        </svg>
                                                    </span>
                                                @endif
                                            </div>
                                        </div>
                                        <div class="min-w-0 flex-1 px-4 md:grid md:grid-cols-4 md:gap-4">
                                            <div class="md:col-span-2">
                                                <div class="text-sm leading-5 font-medium text-gray-900 truncate">
                                                    {{ $item->name }}
                                                </div>
                                                <div class="mt-1 flex items-center text-xs leading-4 text-gray-500">
                                                    <span class="truncate">{{ $item->product->sku ?? '' }}</span>
                                                </div>
                                            </div>
                                            <div class="hidden md:block">
                                                <span class="text-sm leading-5 text-gray-500">
                                                    {{ shopper_money_format($item->unit_price_amount) }} x {{ $item->quantity }}
                                                </span>
                                            </div>
                                        </div>
                                    </div>
                                    <div>
                                        <span class="text-sm leading-5 text-gray-500">
                                            {{ shopper_money_format($item->total) }}
                                        </span>
                                    </div>
                                </div>
                            </li>
                        @endforeach
                    </ul>
                    <div class="mt-2 pt-3 border-t border-gray-200">
                        {{ $items->links() }}
                    </div>
                </div>
                <div class="mt-3 flex justify-end">
                    <div class="sm:max-w-sm space-y-1 text-right">
                        <div class="bg-gray-200 rounded-md p-4 text-right">
                            <span class="text-base leading-6 font-semibold text-gray-900">{{ __('Items total:') }} </span>
                            {{ $order->total }}
                        </div>
                        <p class="text-sm text-gray-500 leading-5">{{ __('This price does not include applicable taxes on the product or on the customer.') }}</p>
                    </div>
                </div>
            </div>
            <div class="py-4 sm:pr-8">
                <h3 class="text-lg leading-6 font-medium text-gray-900">{{ __('Payment Method') }}</h3>
                <div class="mt-4">
                    <div class="py-4 flex">
                        @if($order->paymentMethod->logo)
                            <img class="h-10 w-10 rounded-md object-cover" src="{{ $order->paymentMethod->logo_url }}" alt="payment icon" />
                        @else
                            <span class="flex items-center justify-center h-10 w-10 bg-gray-100 text-gray-300 rounded-md">
                                <svg fill="none" stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" viewBox="0 0 24 24" class="w-6 h-6">
                                    <path d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z" />
                                </svg>
                            </span>
                        @endif
                        <div class="ml-4">
                            <p class="text-sm font-medium text-gray-900">{{ $order->paymentMethod->title }}</p>
                            <a href="{{ route('shopper.settings.payments') }}" class="text-sm text-gray-500 hover:text-gray-400 underline">{{ __('View available methods') }}</a>
                        </div>
                    </div>
                </div>
            </div>
            <div class="py-4 sm:pr-8">
                <h3 class="text-lg leading-6 font-medium text-gray-900">{{ __('Shipping') }}</h3>
                <div class="mt-4">
                    @if($order->shipping_method)
                        <dl>
                            <div class="bg-gray-50 py-4 sm:grid sm:grid-cols-3 sm:gap-4 px-4">
                                <dt class="text-sm font-medium text-gray-500">
                                    {{ __('Provider') }}
                                </dt>
                                <dd class="mt-1 text-sm font-medium text-gray-900 sm:mt-0 sm:col-span-2">
                                    {{ $order->shipping_method }}
                                </dd>
                            </div>
                            <div class="bg-gray-100 py-4 sm:grid sm:grid-cols-3 sm:gap-4 px-4">
                                <dt class="text-sm font-medium text-gray-500">
                                    {{ __('Price') }}
                                </dt>
                                <dd class="mt-1 text-sm font-medium text-gray-900 sm:mt-0 sm:col-span-2">
                                    {{ shopper_money_format($order->shipping_total) }}
                                </dd>
                            </div>
                            <div class="bg-gray-50 py-4 sm:grid sm:grid-cols-3 sm:gap-4 px-4">
                                <dt class="text-sm font-medium text-gray-500">
                                    {{ __('Tax') }}
                                </dt>
                                <dd class="mt-1 text-sm font-medium text-gray-900 sm:mt-0 sm:col-span-2">
                                    0.00
                                </dd>
                            </div>
                        </dl>
                    @else
                        <div class="bg-yellow-50 border-l-4 border-yellow-400 p-4">
                            <div class="flex">
                                <div class="flex-shrink-0">
                                    <svg class="h-5 w-5 text-yellow-400" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor" aria-hidden="true">
                                        <path fill-rule="evenodd" d="M8.257 3.099c.765-1.36 2.722-1.36 3.486 0l5.58 9.92c.75 1.334-.213 2.98-1.742 2.98H4.42c-1.53 0-2.493-1.646-1.743-2.98l5.58-9.92zM11 13a1 1 0 11-2 0 1 1 0 012 0zm-1-8a1 1 0 00-1 1v3a1 1 0 002 0V6a1 1 0 00-1-1z" clip-rule="evenodd" />
                                    </svg>
                                </div>
                                <div class="ml-3">
                                    <p class="text-sm text-yellow-700">
                                        {{ __("This order don't have a shipping method.") }}
                                        <a href="#" class="font-medium underline text-yellow-700 hover:text-yellow-600">
                                            {{ __('Read more about shipping.') }}
                                        </a>
                                    </p>
                                </div>
                            </div>
                        </div>

                    @endif
                </div>
            </div>
            <div class="py-4 sm:pr-8">
                <div class="flex justify-end">
                    <div class="sm:max-w-sm space-y-1 text-right text-gray-500">
                        <div class="bg-gray-200 rounded-md p-4">
                            <span class="text-xl leading-7 font-bold text-gray-900">{{ __('Order total:') }} </span>
                            <span class="font-medium">{{ shopper_money_format($order->fullPriceWithShipping()) }}</span>
                        </div>
                    </div>
                </div>
            </div>
            <div class="py-4 sm:pr-8">
                <h3 class="text-lg leading-6 font-medium text-gray-900">{{ __('Private notes') }}</h3>
                <div class="mt-5 flex space-x-3">
                    <div class="flex-shrink-0">
                        <div class="relative">
                            <img class="h-10 w-10 rounded-full bg-gray-400 flex items-center justify-center ring-8 ring-white" src="https://images.unsplash.com/photo-1517365830460-955ce3ccd263?ixlib=rb-=eyJhcHBfaWQiOjEyMDd9&amp;auto=format&amp;fit=facearea&amp;facepad=8&amp;w=256&amp;h=256&amp;q=80" alt="">
                            <span class="absolute -bottom-0.5 bg-gray-100 right-0 rounded-tl p-0.5">
                                <svg class="h-5 w-5 text-gray-400" x-description="Heroicon name: solid/chat-alt" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor" aria-hidden="true">
                                    <path fill-rule="evenodd" d="M18 5v8a2 2 0 01-2 2h-5l-5 4v-4H4a2 2 0 01-2-2V5a2 2 0 012-2h12a2 2 0 012 2zM7 8H5v2h2V8zm2 0h2v2H9V8zm6 0h-2v2h2V8z" clip-rule="evenodd" />
                                </svg>
                            </span>
                        </div>
                    </div>
                    <div class="min-w-0 flex-1">
                        @if($order->notes)
                            <p class="text-sm leading-5 text-gray-500">{{ $order->notes }}</p>
                        @else
                            <div>
                                <label for="comment" class="sr-only">{{ __('Comment') }}</label>
                                <x-shopper-input.textarea wire:model="notes" id="comment" placeholder="{{ __('Leave notes for this customer') }}" :value="$order->notes" />
                                @error('notes')
                                    <p class="mt-1 text-red-500 text-sm">{{ $message }}</p>
                                @enderror
                            </div>
                            <div class="mt-6 flex items-center justify-end space-x-4">
                                <x-shopper-button wire:click="leaveNotes" wire:loading.attr="disabled" type="button">
                                    <x-shopper-loader wire:loading wire:target="leaveNotes" />
                                    {{ __('Send notes') }}
                                </x-shopper-button>
                            </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>
        <div class="sm:col-span-2 border-t py-2 sm:border-t-0 sm:border-l border-gray-200 sm:pl-8 divide-y divide-gray-200">
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
