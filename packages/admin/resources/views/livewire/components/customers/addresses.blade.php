<x-shopper::container>
    <div class="grid grid-cols-1 gap-x-4 gap-y-6 sm:grid-cols-3">
        @forelse ($this->addresses as $address)
            <x-shopper::card>
                <div class="min-w-0 flex-1">
                    <div class="focus:outline-none">
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
                            {{ $address->full_name }}
                        </h4>
                        <div class="mt-1 text-sm">
                            <p class="text-gray-500 dark:text-gray-400">
                                {{ $address->street_address }}
                            </p>
                            <div
                                class="mt-1 flex flex-col space-y-0.5 truncate text-sm text-gray-500 dark:text-gray-400"
                            >
                                <span>
                                    {{ $address->postal_code }},
                                    {{ $address->city }}
                                </span>

                                @if ($address->country)
                                    <span class="inline-flex shrink-0 items-center gap-2">
                                        <img
                                            src="{{ $address->country->svg_flag }}"
                                            class="size-4 rounded-full object-cover object-center"
                                            alt="Country flag"
                                        />
                                        {{ $address->country->translated_name }}
                                    </span>
                                @endif

                                @if ($address->phone_number)
                                    <span>{{ $address->phone_number }}</span>
                                @endif
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
