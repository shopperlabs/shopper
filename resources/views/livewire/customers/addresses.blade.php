<div>
    <div class="grid grid-cols-1 gap-4 sm:grid-cols-3">
        @forelse($addresses as $address)
            <div class="relative flex items-center px-6 py-5 rounded-lg bg-white shadow dark:bg-secondary-800">
                <div class="flex-1 min-w-0">
                    <div class="focus:outline-none">
                        <span class="absolute inset-0" aria-hidden="true"></span>
                        <div class="flex items-center justify-between space-x-2">
                            <span class="inline-flex text-xs leading-4 text-secondary-500 dark:text-secondary-400">
                                {{ $address->type === 'shipping' ? __('Shipping Address') : __('Billing Address') }}
                            </span>
                            @if($address->is_default)
                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-800">
                                    {{ __('Default address') }}
                                </span>
                            @endif
                        </div>
                        <h4 class="mt-1 block text-sm font-medium text-secondary-900 dark:text-white">
                            {{ $address->last_name. ' ' .$address->first_name }}
                        </h4>
                        <div class="mt-1 text-sm leading-5">
                            <p class="text-secondary-500 dark:text-secondary-400">
                                {{ $address->street_address }}, {{ $address->city }}
                            </p>
                            <div class="text-sm text-secondary-500 truncate dark:text-secondary-400">
                                <span>{{ $address->phone_number }}</span> <br />
                                <span>{{ $address->zipcode }}</span>,
                                <span>{{ $address->country->name }}</span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        @empty
            <div class="sm:col-span-3 relative rounded-lg bg-white px-6 py-10 sm:py-12 shadow flex flex-col items-center dark:bg-secondary-800">
                <div class="flex-shrink-0 h-24 w-24 mx-auto text-secondary-400">
                    <x-heroicon-o-map class="w-full h-full" />
                </div>
                <div class="mt-5 w-full sm:max-w-md space-y-2 text-center">
                    <p class="text-base leading-6 text-secondary-900 font-medium dark:text-white">{{ __('Customer addresses') }}</p>
                    <p class="text-sm text-secondary-500 dark:text-secondary-400">{{ __('This customer does not yet have a delivery or billing address.') }}</p>
                </div>
            </div>
        @endforelse
    </div>
</div>
