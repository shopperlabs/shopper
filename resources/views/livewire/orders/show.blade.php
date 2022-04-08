<div>
    <x-shopper::breadcrumb back="shopper.orders.index">
        <svg class="shrink-0 h-5 w-5 text-secondary-400" fill="currentColor" viewBox="0 0 20 20">
            <path fill-rule="evenodd" d="M12.707 5.293a1 1 0 010 1.414L9.414 10l3.293 3.293a1 1 0 01-1.414 1.414l-4-4a1 1 0 010-1.414l4-4a1 1 0 011.414 0z" clip-rule="evenodd"/>
        </svg>
        <a href="{{ route('shopper.orders.index') }}" class="text-secondary-500 dark:text-secondary-400 hover:text-secondary-700 focus:outline-none focus:underline transition duration-150 ease-in-out">{{ __('Orders') }}</a>
    </x-shopper::breadcrumb>

    <div class="mt-3 bg-secondary-100 z-30 relative pb-5 border-b border-secondary-200 sticky top-0 -my-2 pt-4 sm:pt-1 sm:-my-0 sm:-mx-8 dark:bg-secondary-900 dark:border-secondary-700">
        <div class="sm:px-8 space-y-4">
            <div class="space-y-3 md:flex md:items-center md:justify-between md:space-y-0">
                <div class="flex-1 flex items-center space-x-4 min-w-0">
                    <h3 class="text-2xl font-bold leading-6 text-secondary-900 dark:text-white sm:text-3xl sm:leading-9 sm:truncate">{{ $order->number }}</h3>
                    <div class="p-1 flex items-center divide-x-2 divide-secondary-200 dark:divide-secondary-700">
                        <div class="flex items-center space-x-2 pr-4">
                            <span class="inline-flex items-center px-2.5 py-0.5 border-2 rounded-full text-xs font-medium {{ $order->status_classes }}">
                                {{ $order->formatted_status }}
                            </span>
                        </div>
                        <div class="flex items-center px-4">
                            <svg class="h-5 w-5 mr-1.5 text-secondary-500 dark:text-secondary-400" fill="currentColor" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg">
                                <path fill-rule="evenodd" d="M6 2a1 1 0 00-1 1v1H4a2 2 0 00-2 2v10a2 2 0 002 2h12a2 2 0 002-2V6a2 2 0 00-2-2h-1V3a1 1 0 10-2 0v1H7V3a1 1 0 00-1-1zm0 5a1 1 0 000 2h8a1 1 0 100-2H6z" clip-rule="evenodd" />
                            </svg>
                            <span class="text-secondary-700 font-medium text-sm leading-6 dark:text-secondary-300">
                                <time datetime="{{ $order->created_at->format('F j, Y h:m') }}">
                                    {{ $order->created_at->formatLocalized('%d %B %G - %H:%M') }}
                                </time>
                            </span>
                        </div>
                    </div>
                </div>
                <div class="flex space-x-3">
                    @if(! $order->isCompleted())

                        @if(! $order->isPaid())
                            <span class="hidden sm:block">
                                <x-shopper::buttons.danger wire:click="$emit('openModal', 'shopper-modals.archived-order', {{ json_encode([$order->id]) }})" type="button">
                                    <x-heroicon-s-archive class="w-5 h-5 -ml-1 mr-2" />
                                    {{ __('Archived') }}
                                </x-shopper::buttons.danger>
                            </span>
                        @endif

                        <x-shopper::dropdown>
                            <x-slot name="trigger">
                                <x-shopper::buttons.default>
                                    {{ __('More actions') }}
                                    <svg class="w-5 h-5 -mr-1 ml-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 9l4-4 4 4m0 6l-4 4-4-4" />
                                    </svg>
                                </x-shopper::buttons.default>
                            </x-slot>

                            <x-slot name="content">
                                <div class="py-1">
                                    @if($order->isPending())
                                        <x-shopper::dropdown-button wire:click="register" role="menuitem">
                                            {{ __('Register') }}
                                        </x-shopper::dropdown-button>
                                    @endif

                                    @if($order->isPending() || $order->isRegister())
                                        <x-shopper::dropdown-button wire:click="markPaid" role="menuitem">
                                            {{ __('Mark as paid') }}
                                        </x-shopper::dropdown-button>
                                    @endif

                                    @if($order->isPaid())
                                        <x-shopper::dropdown-button wire:click="markComplete" role="menuitem">
                                            <x-heroicon-o-check-circle class="mr-3 h-5 w-5 text-secondary-400 dark:text-secondary-500 group-hover:text-secondary-500 dark:text-secondary-500" />
                                            {{ __('Mark complete') }}
                                        </x-shopper::dropdown-button>
                                    @endif

                                    @if($order->canBeCancelled())
                                        <x-shopper::dropdown-button wire:click="cancelOrder" role="menuitem">
                                            <x-heroicon-s-x class="mr-3 h-5 w-5 text-secondary-400 dark:text-secondary-500 group-hover:text-secondary-500 dark:text-secondary-500" />
                                            {{ __('Cancel Order') }}
                                        </x-shopper::dropdown-button>
                                    @endif
                                </div>
                            </x-slot>
                        </x-shopper::dropdown>
                    @endif

                    <span class="relative z-0 inline-flex shadow-sm">
                        <button @if($prevOrder) wire:click="goToOrder({{ $prevOrder->id }})" @endif type="button" class="relative inline-flex items-center px-2 py-2 rounded-l-md border border-secondary-300 dark:border-secondary-700 bg-white dark:bg-secondary-800 text-sm leading-5 font-medium text-secondary-500 dark:text-secondary-400 hover:text-secondary-400 dark:hover:text-secondary-500 focus:z-10 focus:outline-none focus:border-primary-300 focus:shadow-outline-primary dark:text-secondary-400 transition ease-in-out duration-150 @if(! $prevOrder) bg-secondary-100 dark:bg-secondary-800 hover:text-secondary-500 dark:text-secondary-400 disabled:opacity-50 @endif" aria-label="{{ __('Previous order') }}" @if(! $prevOrder) disabled @endif>
                            <svg class="h-5 w-5" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor">
                                <path fill-rule="evenodd" d="M12.707 5.293a1 1 0 010 1.414L9.414 10l3.293 3.293a1 1 0 01-1.414 1.414l-4-4a1 1 0 010-1.414l4-4a1 1 0 011.414 0z" clip-rule="evenodd" />
                            </svg>
                        </button>
                        <button @if($nextOrder) wire:click="goToOrder({{ $nextOrder->id }})" @endif type="button" class="-ml-px relative inline-flex items-center px-2 py-2 rounded-r-md border border-secondary-300 dark:border-secondary-700 bg-white dark:bg-secondary-800 text-sm leading-5 font-medium text-secondary-500 dark:text-secondary-400 hover:text-secondary-400 dark:hover:text-secondary-500 focus:z-10 focus:outline-none focus:border-primary-300 focus:shadow-outline-primary dark:text-secondary-400 transition ease-in-out duration-150 @if(! $nextOrder) bg-secondary-100 dark:bg-secondary-800 hover:text-secondary-500 dark:text-secondary-400 disabled:opacity-50 @endif" aria-label="{{ __('Next order') }}" @if(! $nextOrder) disabled @endif>
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
        <div class="sm:col-span-4 py-2 divide-y divide-secondary-200 dark:divide-secondary-700">
            <div class="py-4 sm:pr-8">
                <div class="flex items-center justify-between">
                    <h3 class="text-lg leading-6 font-medium text-secondary-900 dark:text-white">{{ __('Products') }}</h3>
                    <div class="flex items-center space-x-3">
                        <span class="whitespace-no-wrap text-sm font-medium text-secondary-500 dark:text-secondary-400">{{ __('Per Page') }}</span>
                        <x-shopper::forms.select wire:model="perPage" class="w-20" aria-label="{{ __('Per Page items') }}">
                            <option value="3">3</option>
                            <option value="5">5</option>
                            <option value="10">10</option>
                        </x-shopper::forms.select>
                    </div>
                </div>
                <div class="mt-4">
                    <ul class="divide-y divide-secondary-200 dark:divide-secondary-700">
                        @foreach($items as $item)
                            <li class="flex-1 py-2.5">
                                <div class="flex items-center">
                                    <div class="min-w-0 flex-1 flex items-center">
                                        <div class="shrink-0">
                                            <div class="shrink-0 h-10 w-10">
                                                @if($item->product->getFirstMediaUrl(config('shopper.system.storage.disks.uploads')))
                                                    <img class="h-10 w-10 rounded-md object-cover" src="{{ $item->product->getFirstMediaUrl(config('shopper.system.storage.disks.uploads')) }}" alt="{{ $item->id }}" />
                                                @else
                                                    <span class="flex items-center justify-center h-10 w-10 bg-secondary-100 text-secondary-400 rounded-md dark:bg-secondary-800 dark:text-secondary-500">
                                                        <svg fill="none" stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" viewBox="0 0 24 24" class="w-6 h-6">
                                                            <path d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z" />
                                                        </svg>
                                                    </span>
                                                @endif
                                            </div>
                                        </div>
                                        <div class="min-w-0 flex-1 px-4 md:grid md:grid-cols-4 md:gap-4">
                                            <div class="md:col-span-2">
                                                <div class="text-sm leading-5 font-medium text-secondary-900 dark:text-white truncate">
                                                    {{ $item->name }}
                                                </div>
                                                <div class="mt-1 flex items-center text-xs leading-4 text-secondary-500 dark:text-secondary-400">
                                                    <span class="truncate">{{ $item->product->sku ?? '' }}</span>
                                                </div>
                                            </div>
                                            <div class="hidden md:block">
                                                <span class="text-sm leading-5 text-secondary-500 dark:text-secondary-400">
                                                    {{ shopper_money_format($item->unit_price_amount) }} x {{ $item->quantity }}
                                                </span>
                                            </div>
                                        </div>
                                    </div>
                                    <div>
                                        <span class="text-sm leading-5 text-secondary-500 dark:text-secondary-400">
                                            {{ shopper_money_format($item->total) }}
                                        </span>
                                    </div>
                                </div>
                            </li>
                        @endforeach
                    </ul>
                    <div class="mt-2 pt-3 border-t border-secondary-200 dark:border-secondary-700">
                        {{ $items->links() }}
                    </div>
                </div>
                <div class="mt-3 flex justify-end">
                    <div class="w-full sm:max-w-sm space-y-1 text-right">
                        <div class="bg-secondary-200 rounded-md p-4 text-secondary-700 dark:text-secondary-300 dark:bg-secondary-800">
                            <span class="text-base leading-6 font-semibold text-secondary-900 dark:text-white">{{ __('Items total:') }} </span>
                            {{ $order->total }}
                        </div>
                        <p class="text-sm text-secondary-500 dark:text-secondary-400 leading-5">{{ __('This price does not include applicable taxes on the product or on the customer.') }}</p>
                    </div>
                </div>
            </div>
            <div class="py-4 sm:pr-8">
                <h3 class="text-lg leading-6 font-medium text-secondary-900 dark:text-white">{{ __('Payment Method') }}</h3>
                <div class="mt-4">
                    <div class="py-4 flex">
                        @if($order->paymentMethod->logo)
                            <img class="h-10 w-10 rounded-md object-cover" src="{{ $order->paymentMethod->logo_url }}" alt="payment icon" />
                        @else
                            <span class="flex items-center justify-center h-10 w-10 bg-secondary-100 text-secondary-300 rounded-md dark:bg-secondary-800 dark:text-white">
                                <svg fill="none" stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" viewBox="0 0 24 24" class="w-6 h-6">
                                    <path d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z" />
                                </svg>
                            </span>
                        @endif
                        <div class="ml-4">
                            <p class="text-sm font-medium text-secondary-900 dark:text-white">{{ $order->paymentMethod->title }}</p>
                            <a href="{{ route('shopper.settings.payments') }}" class="text-sm text-secondary-500 dark:text-secondary-400 hover:text-secondary-400 underline">{{ __('View available methods') }}</a>
                        </div>
                    </div>
                </div>
            </div>
            <div class="py-4 sm:pr-8">
                <h3 class="text-lg leading-6 font-medium text-secondary-900 dark:text-white">{{ __('Shipping') }}</h3>
                <div class="mt-4">
                    @if($order->shipping_method)
                        <dl>
                            <div class="bg-secondary-50 py-4 sm:grid sm:grid-cols-3 sm:gap-4 px-4 dark:bg-secondary-800">
                                <dt class="text-sm font-medium text-secondary-500 dark:text-secondary-400">
                                    {{ __('Provider') }}
                                </dt>
                                <dd class="mt-1 text-sm font-medium text-secondary-900 dark:text-white sm:mt-0 sm:col-span-2">
                                    {{ $order->shipping_method }}
                                </dd>
                            </div>
                            <div class="bg-secondary-100 py-4 sm:grid sm:grid-cols-3 sm:gap-4 px-4 dark:bg-secondary-700">
                                <dt class="text-sm font-medium text-secondary-500 dark:text-secondary-400">
                                    {{ __('Price') }}
                                </dt>
                                <dd class="mt-1 text-sm font-medium text-secondary-900 dark:text-white sm:mt-0 sm:col-span-2">
                                    {{ shopper_money_format($order->shipping_total) }}
                                </dd>
                            </div>
                            <div class="bg-secondary-50 py-4 sm:grid sm:grid-cols-3 sm:gap-4 px-4 dark:bg-secondary-800">
                                <dt class="text-sm font-medium text-secondary-500 dark:text-secondary-400">
                                    {{ __('Tax') }}
                                </dt>
                                <dd class="mt-1 text-sm font-medium text-secondary-900 dark:text-white sm:mt-0 sm:col-span-2">
                                    0.00
                                </dd>
                            </div>
                        </dl>
                    @else
                        <div class="bg-yellow-50 border-l-4 border-yellow-400 p-4">
                            <div class="flex">
                                <div class="shrink-0">
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
                    <div class="w-full sm:max-w-sm space-y-1 text-right text-secondary-700 dark:text-secondary-300">
                        <div class="bg-secondary-200 rounded-md p-4 dark:bg-secondary-800">
                            <span class="text-base leading-6 font-semibold text-secondary-900 dark:text-white">{{ __('Order total:') }} </span>
                            {{ shopper_money_format($order->fullPriceWithShipping()) }}
                        </div>
                    </div>
                </div>
                <div class="flex justify-end mt-2 text-right">
                    <x-shopper::dropdown>
                        <x-slot name="trigger">
                            <x-shopper::buttons.default>
                                {{ __('Payment actions') }}
                                <svg class="w-5 h-5 -mr-1 ml-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 9l4-4 4 4m0 6l-4 4-4-4" />
                                </svg>
                            </x-shopper::buttons.default>
                        </x-slot>

                        <x-slot name="content">
                            <div class="py-1">
                                <x-shopper::dropdown-button role="menuitem">
                                    {{ __('Send invoice') }}
                                    <span class="inline-flex items-center ml-3 px-2.5 py-0.5 rounded-full text-xs font-medium leading-4 bg-secondary-100 text-secondary-800 dark:bg-secondary-800 dark:text-secondary-300">
                                      {{ __('Soon') }}
                                    </span>
                                </x-shopper::dropdown-button>
                                @if($order->isPending())
                                    <x-shopper::dropdown-button wire:click="register" role="menuitem">
                                        {{ __('Register') }}
                                    </x-shopper::dropdown-button>
                                @endif
                                @if($order->isPending() || $order->isRegister())
                                    <x-shopper::dropdown-button wire:click="markPaid" role="menuitem">
                                        {{ __('Mark as paid') }}
                                    </x-shopper::dropdown-button>
                                @endif
                                @if($order->isPaid())
                                    <x-shopper::dropdown-button wire:click="markComplete" role="menuitem">
                                        {{ __('Mark complete') }}
                                    </x-shopper::dropdown-button>
                                @endif
                            </div>
                        </x-slot>
                    </x-shopper::dropdown>
                </div>
            </div>
            <div class="py-4 sm:pr-8">
                <h3 class="text-lg leading-6 font-medium text-secondary-900 dark:text-white">{{ __('Private notes') }}</h3>
                <div class="mt-5 flex space-x-3">
                    <div class="shrink-0">
                        <div class="relative">
                            <img class="h-10 w-10 rounded-full bg-secondary-400 flex items-center justify-center ring-8 ring-white" src="{{ auth()->user()->picture }}" alt="">
                            <span class="absolute -bottom-0.5 bg-secondary-100 right-0 rounded-tl p-0.5">
                                <x-heroicon-s-chat-alt class="h-5 w-5 text-secondary-400 dark:text-secondary-500" />
                            </span>
                        </div>
                    </div>
                    <div class="min-w-0 flex-1">
                        @if($order->notes)
                            <p class="text-sm leading-5 text-secondary-500 dark:text-secondary-400">{{ $order->notes }}</p>
                        @else
                            <div>
                                <label for="comment" class="sr-only">{{ __('Comment') }}</label>
                                <x-shopper::forms.textarea wire:model.lazy="notes" id="comment" placeholder="{{ __('Leave notes for this customer') }}" :value="$order->notes" />
                                @error('notes')
                                    <p class="mt-1 text-red-500 text-sm">{{ $message }}</p>
                                @enderror
                            </div>
                            <div class="mt-6 flex items-center justify-end space-x-4">
                                <x-shopper::buttons.primary wire:click="leaveNotes" wire:loading.attr="disabled" type="button">
                                    <x-shopper::loader wire:loading wire:target="leaveNotes" />
                                    {{ __('Send notes') }}
                                </x-shopper::buttons.primary>
                            </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>
        <div class="sm:col-span-2 border-t py-2 sm:border-t-0 sm:border-l border-secondary-200 sm:pl-8 divide-y divide-secondary-200 dark:border-secondary-700 dark:divide-secondary-700">
            <div class="py-4">
                <h3 class="text-lg leading-6 font-medium text-secondary-900 dark:text-white">{{ __('Customer') }}</h3>
                <div class="mt-4 flex items-center space-x-4">
                    <div class="shrink-0">
                        <img class="h-8 w-8 rounded-full" src="{{ $order->customer->picture }}" alt="Customer profile">
                    </div>
                    <div class="flex-1 min-w-0">
                        <p class="text-sm font-medium text-secondary-900 dark:text-white truncate">{{ $order->customer->full_name }}</p>
                    </div>
                    <div>
                        <a href="{{ route('shopper.customers.show', $order->customer) }}" class="inline-flex items-center shadow-sm px-2.5 py-0.5 border border-secondary-300 text-sm leading-5 font-medium rounded-full text-secondary-700 bg-white hover:bg-secondary-50 dark:border-secondary-700 dark:text-secondary-300 dark:bg-secondary-800 dark:hover:bg-secondary-700">
                            {{ __('View') }}
                        </a>
                    </div>
                </div>
                <div class="mt-3">
                    <p class="text-sm text-secondary-500 dark:text-secondary-400 leading-5">
                        {{ __('Customer for :date', ['date' => $order->customer->created_at->diffForHumans()]) }}
                    </p>
                    <p class="text-sm text-secondary-500 dark:text-secondary-400 leading-5">
                        {{ __('This customer has already placed :number order(s)', ['number' => $order->customer->orders->count()]) }}
                    </p>
                </div>
            </div>
            <div class="py-4">
                <h3 class="text-xs uppercase tracking-wider font-medium leading-4 text-secondary-900 dark:text-white">{{ __('Contact Information') }}</h3>
                <div class="mt-3 space-y-1">
                    <p class="text-sm leading-5 text-secondary-500 dark:text-secondary-400">
                        <a href="mailto:{{ $order->customer->email }}" class="underline text-primary-600 hover:text-primary-500">{{ $order->customer->email }}</a>
                    </p>
                    <p class="text-sm leading-5 text-secondary-500 dark:text-secondary-400">{{ $order->customer->phone_number ?? __('No phone number') }}</p>
                </div>
            </div>
            <div class="py-4">
                <h3 class="text-xs uppercase tracking-wider font-medium leading-4 text-secondary-900 dark:text-white">{{ __('Shipping Address') }}</h3>
                <p class="mt-3 text-sm text-secondary-500 dark:text-secondary-400 leading-5">
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
                <h3 class="text-xs uppercase tracking-wider font-medium leading-4 text-secondary-900 dark:text-white">{{ __('Billing Address') }}</h3>
                @if(! $billingAddress || ($billingAddress && $billingAddress->id === $order->shippingAddress->id ))
                    <p class="mt-3 text-secondary-500 dark:text-secondary-400 text-sm leading-5">{{ __('Same as shipping address') }}</p>
                @else
                    <p class="mt-3 text-sm text-secondary-500 dark:text-secondary-400 leading-5">
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
