<div
    x-data="{
        open: false,
        options: ['profile', 'address', 'orders'],
        words: {
            'profile': '{{ __('shopper::pages/customers.profile.title') }}',
            'address': '{{ __('shopper::pages/customers.addresses.title') }}',
            'orders': '{{ __('shopper::layout.sidebar.orders') }}'
        },
        currentTab: 'profile',
        activeTab(tab) {
            return this.currentTab === tab;
        },
    }"
>
    <x-shopper::container>
        <x-shopper::breadcrumb :back="route('shopper.customers.index')" :current="$customer->full_name">
            <x-heroicon-s-chevron-left class="shrink-0 h-4 w-4 text-secondary-300 dark:text-secondary-600" />
            <x-shopper::breadcrumb.link :link="route('shopper.customers.index')" :title="__('shopper::layout.sidebar.customers')" />
        </x-shopper::breadcrumb>

        <div class="mt-5 lg:flex lg:items-center lg:justify-between relative z-20">
            <div class="flex-1 min-w-0">
                <div class="flex items-start">
                    <div class="shrink-0 h-12 w-12">
                        <img class="h-12 w-12 rounded-lg" src="{{ $picture }}" alt="">
                    </div>
                    <div class="ml-4">
                        <h3 class="text-2xl font-bold leading-6 text-secondary-900 sm:truncate dark:text-white">
                            {{ $customer->full_name }}
                        </h3>
                        <div class="mt-1 flex items-center divide-x divide-secondary-200 dark:divide-secondary-700">
                            <div class="flex items-center pr-2">
                                @if($customer->email_verified_at)
                                    <x-heroicon-s-shield-check class="w-5 h-5 text-green-500" />
                                @else
                                    <x-heroicon-s-shield-exclamation class="w-5 h-5 text-red-500" />
                                @endif
                                <span class="ml-1.5 text-sm leading-5 text-secondary-500 dark:text-secondary-400">
                                    {{ $customer->email }}
                                </span>
                            </div>
                            <p class="pl-2 text-sm text-secondary-500 leading-5 dark:text-secondary-400">
                                {{ __('shopper::pages/customers.period', ['period' => $customer->created_at->diffForHumans()]) }}
                            </p>
                        </div>
                    </div>
                </div>
            </div>
            <div class="hidden lg:flex mt-4 lg:mt-0 lg:ml-4 space-x-2">
                <div @keydown.escape="open = false" @click.away="open = false" class="relative inline-block text-left">
                    <div>
                        <button @click="open = !open" class="flex items-center text-secondary-400 hover:text-secondary-500 focus:outline-none focus:text-secondary-500" aria-label="Options" id="options-menu" aria-haspopup="true" x-bind:aria-expanded="open">
                            <x-heroicon-o-dots-horizontal class="w-5 h-5" />
                        </button>
                    </div>

                    <div x-cloak
                         x-show="open"
                         x-transition:enter="transition ease-out duration-100"
                         x-transition:enter-start="transform opacity-0 scale-95"
                         x-transition:enter-end="transform opacity-100 scale-100"
                         x-transition:leave="transition ease-in duration-75"
                         x-transition:leave-start="transform opacity-100 scale-100"
                         x-transition:leave-end="transform opacity-0 scale-95"
                         class="origin-top-right absolute right-0 mt-2 w-56 rounded-md shadow-lg"
                    >
                        <div class="rounded-md bg-white shadow-xs dark:bg-secondary-800">
                            <div class="py-1" role="menu" aria-orientation="vertical" aria-labelledby="options-menu">
                                <button wire:click="$emit('openModal', 'shopper-modals.delete-customer', {{ json_encode([$customer->id]) }})" type="button" class="block w-full px-4 py-2 text-sm leading-5 text-left text-secondary-700 hover:bg-secondary-100 hover:text-secondary-900 focus:outline-none dark:text-secondary-300 dark:hover:text-white dark:hover:bg-secondary-700" role="menuitem">
                                    {{ __('shopper::layout.forms.actions.delete') }}
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </x-shopper::container>

    <div class="py-6">
        <div class="sticky top-0">
            <div class="lg:border-y lg:border-secondary-200 dark:border-secondary-700">
                <div class="lg:hidden px-4">
                    <x-shopper::forms.select x-model="currentTab" aria-label="{{ __('shopper::words.selected_tab') }}" class="pr-10">
                        <template x-for="option in options" :key="option">
                            <option
                                x-bind:value="option"
                                x-text="words[option]"
                                x-bind:selected="option === currentTab"
                            ></option>
                        </template>
                    </x-shopper::forms.select>
                </div>

                <div class="hidden pt-4 lg:block">
                    <nav class="-mb-px flex space-x-8 px-6">
                        <button
                            @click="currentTab = 'profile'"
                            type="button"
                            class="px-1 pb-4 text-sm font-medium leading-5 whitespace-no-wrap border-b-2 focus:outline-none"
                            :class="activeTab('profile') ? 'border-primary-600 text-primary-500' : 'border-transparent hover:border-secondary-300 dark:hover:border-secondary-400 text-secondary-500 hover:text-secondary-700 dark:text-secondary-400 dark:hover:text-secondary-500'"
                        >
                            {{ __('shopper::pages/customers.profile.title') }}
                        </button>
                        <button
                            @click="currentTab = 'address'"
                            type="button"
                            class="px-1 pb-4 text-sm font-medium leading-5 whitespace-no-wrap border-b-2 focus:outline-none"
                            :class="activeTab('address') ? 'border-primary-600 text-primary-500' : 'border-transparent hover:border-secondary-300 dark:hover:border-secondary-400 text-secondary-500 hover:text-secondary-700 dark:text-secondary-400 dark:hover:text-secondary-500'"
                        >
                            {{ __('shopper::pages/customers.addresses.title') }}
                        </button>
                        <button
                            @click="currentTab = 'orders'"
                            type="button"
                            class="px-1 pb-4 text-sm font-medium leading-5 whitespace-no-wrap border-b-2 focus:outline-none"
                            :class="activeTab('orders') ? 'border-primary-600 text-primary-500' : 'border-transparent hover:border-secondary-300 dark:hover:border-secondary-400 text-secondary-500 hover:text-secondary-700 dark:text-secondary-400 dark:hover:text-secondary-500'"
                        >
                            {{ __('shopper::layout.sidebar.orders') }}
                        </button>
                    </nav>
                </div>
            </div>
        </div>

        <div class="mt-8">
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
