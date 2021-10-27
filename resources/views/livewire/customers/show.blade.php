<div
    x-data="{
        open: false,
        options: ['profile', 'address', 'orders'],
        words: {
            'profile': '{{ __("Profile") }}',
            'address': '{{ __("Address") }}',
            'orders': '{{ __("Orders") }}'
        },
        currentTab: 'profile'
    }"
>
    <x:shopper-breadcrumb back="shopper.customers.index">
        <x-heroicon-s-chevron-left class="flex-shrink-0 h-5 w-5 text-gray-400" />
        <x-shopper-breadcrumb-link :link="route('shopper.customers.index')" title="Customers" />
    </x:shopper-breadcrumb>

    <div class="mt-5 md:flex md:items-center md:justify-between relative z-20">
        <div class="flex-1 min-w-0">
            <div class="flex items-start">
                <div class="flex-shrink-0 h-12 w-12">
                    <img class="h-12 w-12 rounded-lg" src="{{ $picture }}" alt="">
                </div>
                <div class="ml-4">
                    <h3 class="text-2xl font-bold leading-6 text-gray-900 sm:truncate dark:text-white">
                        {{ $customer->full_name }}
                    </h3>
                    <div class="mt-1 flex items-center divide-x divide-gray-200 dark:divide-gray-700">
                        <div class="flex items-center pr-2">
                            @if($customer->email_verified_at)
                                <x-heroicon-s-shield-check class="w-5 h-5 text-green-500" />
                            @else
                                <x-heroicon-s-shield-exclamation class="w-5 h-5 text-red-500" />
                            @endif
                            <span class="ml-1.5 text-sm leading-5 text-gray-500 dark:text-gray-400">{{ $customer->email }}</span>
                        </div>
                        <p class="pl-2 text-sm text-gray-500 leading-5 dark:text-gray-400">
                            {{ __('Customer for :period', ['period' => $customer->created_at->diffForHumans()]) }}
                        </p>
                    </div>
                </div>
            </div>
        </div>
        <div class="hidden md:flex mt-4 flex md:mt-0 md:ml-4 space-x-2">
            <div @keydown.escape="open = false" @click.away="open = false" class="relative inline-block text-left">
                <div>
                    <button @click="open = !open" class="flex items-center text-gray-400 hover:text-gray-500 focus:outline-none focus:text-gray-500" aria-label="Options" id="options-menu" aria-haspopup="true" x-bind:aria-expanded="open">
                        <x-heroicon-o-dots-horizontal class="w-5 h-5" />
                    </button>
                </div>

                <div x-cloak x-show="open" x-transition:enter="transition ease-out duration-100" x-transition:enter-start="transform opacity-0 scale-95" x-transition:enter-end="transform opacity-100 scale-100" x-transition:leave="transition ease-in duration-75" x-transition:leave-start="transform opacity-100 scale-100" x-transition:leave-end="transform opacity-0 scale-95" class="origin-top-right absolute right-0 mt-2 w-56 rounded-md shadow-lg" style="display: none;">
                    <div class="rounded-md bg-white shadow-xs dark:bg-gray-800">
                        <div class="py-1" role="menu" aria-orientation="vertical" aria-labelledby="options-menu">
                            <button wire:click="$emit('openModal', 'shopper-modals.delete-customer', {{ json_encode([$customer->id]) }})" type="button" class="block w-full px-4 py-2 text-sm leading-5 text-left text-gray-700 hover:bg-gray-100 hover:text-gray-900 focus:outline-none dark:text-gray-300 dark:hover:text-white dark:hover:bg-gray-700" role="menuitem">
                                {{ __('Delete customer') }}
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="py-6">
        <div class="sticky top-0 -mx-6 px-6 bg-gray-100 dark:bg-gray-900">
            <div class="border-b border-gray-200 dark:border-gray-700">
                <div class="sm:hidden">
                    <x-shopper-input.select x-model="currentTab" aria-label="Selected tab" class="pr-10">
                        <template x-for="option in options" :key="option">
                            <option
                                x-bind:value="option"
                                x-text="words[option]"
                                x-bind:selected="option === currentTab"
                            ></option>
                        </template>
                    </x-shopper-input.select>
                </div>
                <!-- Tabs at small breakpoint and up -->
                <div class="hidden sm:block">
                    <nav class="-mb-px flex space-x-8">
                        <button @click="currentTab = 'profile'" type="button" class="whitespace-no-wrap pb-4 px-1 border-b-2 border-transparent font-medium text-sm leading-5 text-gray-500 hover:text-gray-700 hover:border-gray-300 dark:text-gray-400 dark:hover:text-gray-500 dark:hover:border-gray-400 focus:outline-none" aria-current="page" :class="{ 'border-blue-500 text-blue-600 dark:text-blue-600 focus:text-blue-800 focus:border-blue-700 dark:focus:text-blue-800 dark:focus:border-blue-700': currentTab === 'profile' }">
                            {{ __('Profile') }}
                        </button>

                        <button @click="currentTab = 'address'" type="button" class="whitespace-no-wrap pb-4 px-1 border-b-2 border-transparent font-medium text-sm leading-5 text-gray-500 hover:text-gray-700 hover:border-gray-300 dark:text-gray-400 dark:hover:text-gray-500 dark:hover:border-gray-400 focus:outline-none" :class="{ 'border-blue-500 text-blue-600 dark:text-blue-600 focus:text-blue-800 focus:border-blue-700 dark:focus:text-blue-800 dark:focus:border-blue-700': currentTab === 'address' }">
                            {{ __('Address') }}
                        </button>

                        <button @click="currentTab = 'orders'" type="button" class="whitespace-no-wrap pb-4 px-1 border-b-2 border-transparent font-medium text-sm leading-5 text-gray-500 hover:text-gray-700 hover:border-gray-300 dark:text-gray-400 dark:hover:text-gray-500 dark:hover:border-gray-400 focus:outline-none" :class="{ 'border-blue-500 text-blue-600 dark:text-blue-600 focus:text-blue-800 focus:border-blue-700 dark:focus:text-blue-800 dark:focus:border-blue-700': currentTab === 'orders' }">
                            {{ __('Orders') }}
                        </button>
                    </nav>
                </div>
            </div>
        </div>

        <div class="mt-6">
            <div x-show="currentTab === 'profile'">
                <livewire:shopper-customers.profile :customer="$customer" />
            </div>
            <div x-cloak x-show="currentTab === 'address'">
                <livewire:shopper-customers.addresses :adresses="$customer->addresses" />
            </div>
            <div x-cloak x-show="currentTab === 'orders'">
                <livewire:shopper-customers.orders :customer="$customer" />
            </div>
        </div>
    </div>

</div>
