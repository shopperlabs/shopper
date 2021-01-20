<div>
    <div class="grid grid-cols-1 gap-4 sm:grid-cols-3">
        @forelse($addresses as $address)
            <div class="relative rounded-lg bg-white px-6 py-5 shadow flex items-center">
                <div class="flex-1 min-w-0">
                    <div class="focus:outline-none">
                        <span class="absolute inset-0" aria-hidden="true"></span>
                        <div class="flex items-center justify-between space-x-2">
                            <span class="inline-flex text-xs leading-4 text-gray-500">
                                {{ $address->type === 'shipping' ? __('Shipping Address') : __('Billing Address') }}
                            </span>
                            @if($address->is_default)
                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-800">
                                    {{ __('Default address') }}
                                </span>
                            @endif
                        </div>
                        <h4 class="mt-1 block text-sm font-medium text-gray-900">
                            {{ $address->last_name. ' ' .$address->first_name }}
                        </h4>
                        <div class="mt-1 text-sm leading-5">
                            <p class="text-gray-500">
                                {{ $address->street_address }}, {{ $address->city }}
                            </p>
                            <div class="text-sm text-gray-500 truncate">
                                <span>{{ $address->phone_number }}</span> <br />
                                <span>{{ $address->zipcode }}</span>,
                                <span>{{ $address->country->name }}</span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        @empty
            <div class="sm:col-span-3 relative rounded-lg bg-white px-6 py-10 sm:py-12 shadow flex flex-col items-center">
                <div class="flex-shrink-0 h-24 w-24 mx-auto text-cool-gray-400">
                    <svg class="w-full h-full" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 20l-5.447-2.724A1 1 0 013 16.382V5.618a1 1 0 011.447-.894L9 7m0 13l6-3m-6 3V7m6 10l4.553 2.276A1 1 0 0021 18.382V7.618a1 1 0 00-.553-.894L15 4m0 13V4m0 0L9 7" />
                    </svg>
                </div>
                <div class="mt-5 w-full sm:max-w-md space-y-2 text-center">
                    <p class="text-base leading-6 text-gray-900 font-medium">{{ __('Customer addresses') }}</p>
                    <p class="text-sm text-gray-500">{{ __("This customer does not yet have a delivery or billing address.") }}</p>
                </div>
            </div>
        @endforelse
    </div>
</div>
