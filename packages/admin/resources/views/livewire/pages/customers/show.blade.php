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
            <x-untitledui-chevron-left class="shrink-0 h-4 w-4 text-gray-300 dark:text-gray-600" />
            <x-shopper::breadcrumb.link :link="route('shopper.customers.index')" :title="__('shopper::layout.sidebar.customers')" />
        </x-shopper::breadcrumb>

        <div class="py-6 lg:flex lg:items-center lg:justify-between">
            <div class="flex-1 min-w-0">
                <div class="flex items-start">
                    <img
                        class="h-12 w-12 rounded-full object-cover"
                        src="{{ $customer->picture }}"
                        alt="{{ $customer->full_name }}"
                    />
                    <div class="ml-4">
                        <h3 class="text-2xl font-heading font-bold leading-6 text-gray-900 sm:truncate dark:text-white">
                            {{ $customer->full_name }}
                        </h3>
                        <div class="mt-1 flex items-center sm:space-x-2">
                            <div class="flex items-center">
                                @if($customer->email_verified_at)
                                    <x-untitledui-check-verified-02
                                        class="w-5 h-5 text-green-500"
                                        aria-hidden="true"
                                    />
                                @else
                                    <x-untitledui-alert-circle
                                        class="w-5 h-5 text-danger-500"
                                        aria-hidden="true"
                                    />
                                @endif
                                <span class="ml-1.5 text-sm leading-5 text-gray-500 dark:text-gray-400">
                                    {{ $customer->email }}
                                </span>
                            </div>
                            <svg viewBox="0 0 2 2" class="h-0.5 w-0.5 hidden flex-none fill-gray-300 dark:fill-gray-500 sm:block" aria-hidden="true">
                                <circle cx="1" cy="1" r="1" />
                            </svg>
                            <p class="hidden text-sm text-gray-500 leading-5 dark:text-gray-400 sm:block">
                                {{ __('shopper::pages/customers.period', ['period' => $customer->created_at->diffForHumans()]) }}
                            </p>
                        </div>
                    </div>
                </div>
            </div>
            <div class="hidden lg:flex mt-4 lg:mt-0 lg:ml-4 space-x-2">
                <div>
                    <x-filament-actions::group
                        :actions="[
                            $this->deleteAction,
                        ]"
                        :label="__('shopper::layout.forms.actions.more_actions')"
                        icon="heroicon-m-ellipsis-vertical"
                        color="gray"
                        size="sm"
                        :button="true"
                    />

                    <x-filament-actions::modals />
                </div>
            </div>
        </div>
    </x-shopper::container>

    <div>
        <div class="sticky top-0">
            <div class="lg:border-y lg:border-gray-200 dark:border-gray-700">
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
                            :class="activeTab('profile') ? 'border-primary-600 text-primary-500' : 'border-transparent hover:border-gray-300 dark:hover:border-gray-400 text-gray-500 hover:text-gray-700 dark:text-gray-400 dark:hover:text-gray-500'"
                        >
                            {{ __('shopper::pages/customers.profile.title') }}
                        </button>
                        <button
                            @click="currentTab = 'address'"
                            type="button"
                            class="px-1 pb-4 text-sm font-medium leading-5 whitespace-no-wrap border-b-2 focus:outline-none"
                            :class="activeTab('address') ? 'border-primary-600 text-primary-500' : 'border-transparent hover:border-gray-300 dark:hover:border-gray-400 text-gray-500 hover:text-gray-700 dark:text-gray-400 dark:hover:text-gray-500'"
                        >
                            {{ __('shopper::pages/customers.addresses.title') }}
                        </button>
                        <button
                            @click="currentTab = 'orders'"
                            type="button"
                            class="px-1 pb-4 text-sm font-medium leading-5 whitespace-no-wrap border-b-2 focus:outline-none"
                            :class="activeTab('orders') ? 'border-primary-600 text-primary-500' : 'border-transparent hover:border-gray-300 dark:hover:border-gray-400 text-gray-500 hover:text-gray-700 dark:text-gray-400 dark:hover:text-gray-500'"
                        >
                            {{ __('shopper::layout.sidebar.orders') }}
                        </button>
                    </nav>
                </div>
            </div>
        </div>

        <div class="mt-10">
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
