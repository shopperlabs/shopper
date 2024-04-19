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
                                    $address->type === \Shopper\Core\Enum\AddressType::SHIPPING
                                        ? __('shopper::pages/customers.addresses.shipping')
                                        : __('shopper::pages/customers.addresses.billing')
                                }}
                            </span>
                            @if ($address->is_default)
                                <span
                                    class="inline-flex items-center rounded-full bg-green-100 px-2.5 py-0.5 text-xs font-medium text-green-800"
                                >
                                    {{ __('shopper::pages/customers.addresses.default') }}
                                </span>
                            @endif
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
                                <span>{{ isoToEmoji($address->country->cca2) }}</span>
                            </div>
                        </div>
                    </div>
                </div>
            </x-shopper::card>
        @empty
            <div
                class="relative flex flex-col items-center rounded-lg bg-white px-6 py-10 shadow dark:bg-gray-800 sm:col-span-3 sm:py-12"
            >
                <div class="mx-auto h-24 w-24 shrink-0 text-primary-500">
                    <x-heroicon-o-map class="h-full w-full" />
                </div>
                <div class="mt-5 w-full space-y-2 text-center sm:max-w-md">
                    <p class="text-base font-medium leading-6 text-gray-900 dark:text-white">
                        {{ __('shopper::pages/customers.addresses.customer') }}
                    </p>
                    <p class="text-sm text-gray-500 dark:text-gray-400">
                        {{ __('shopper::pages/customers.addresses.empty_text') }}
                    </p>
                </div>
            </div>
        @endforelse
    </div>
</x-shopper::container>
