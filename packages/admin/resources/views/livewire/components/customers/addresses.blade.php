<x-shopper::container>
    <div class="grid grid-cols-1 gap-x-4 gap-y-6 sm:grid-cols-3">
        @forelse ($addresses as $address)
            <x-shopper::card class="relative flex items-center px-6 py-5">
                <div class="min-w-0 flex-1">
                    <div class="focus:outline-none">
                        <span class="absolute inset-0" aria-hidden="true"></span>
                        <div class="flex items-center justify-between space-x-2">
                            <span class="inline-flex text-xs leading-4 text-gray-500 dark:text-gray-400">
                                {{
                                    $address->type === \Shopper\Core\Enum\AddressType::Shipping
                                        ? __('shopper::pages/customers.addresses.shipping')
                                        : __('shopper::pages/customers.addresses.billing')
                                }}
                            </span>
                        </div>
                        <h4 class="mt-1 block text-sm font-medium text-gray-900 dark:text-white">
                            {{ $address->last_name . ' ' . $address->first_name }}
                        </h4>
                        <div class="mt-1 text-sm leading-5">
                            <p class="text-gray-500 dark:text-gray-400">
                                {{ $address->street_address }}, {{ $address->city }}
                            </p>
                            <div class="truncate text-sm text-gray-500 dark:text-gray-400">
                                <span>{{ $address->phone_number }}</span>
                                <br />
                                <span>{{ $address->zipcode }}</span>
                                ,
                                <span>{{ $address->country->name }}</span>
                                <span class="inline-flex h-6 w-6 rounded-full">{{ $address->country->svg_flag }}</span>
                            </div>
                        </div>
                    </div>
                </div>
            </x-shopper::card>
        @empty
            <x-shopper::card class="sm:col-span-3">
                <x-shopper::empty-card
                    icon="heroicon-o-map"
                    :heading="__('shopper::pages/customers.addresses.customer')"
                    :description="__('shopper::pages/customers.addresses.empty_text')"
                />
            </x-shopper::card>
        @endforelse
    </div>
</x-shopper::container>
