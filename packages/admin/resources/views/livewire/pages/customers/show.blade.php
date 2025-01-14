<div x-data="{
        options: ['profile', 'address', 'orders'],
        currentTab: 'profile'
    }"
     class="py-5"
>
    <x-shopper::container>
        <x-shopper::breadcrumb :back="route('shopper.customers.index')" :current="$customer->full_name">
            <x-untitledui-chevron-left class="size-4 shrink-0 text-gray-300 dark:text-gray-600" aria-hidden="true" />
            <x-shopper::breadcrumb.link
                :link="route('shopper.customers.index')"
                :title="__('shopper::pages/customers.menu')"
            />
        </x-shopper::breadcrumb>

        <div class="py-6 lg:flex lg:items-center lg:justify-between">
            <div class="min-w-0 flex-1">
                <div class="flex items-start">
                    <img
                        class="size-12 rounded-full object-cover"
                        src="{{ $customer->picture }}"
                        alt="{{ $customer->full_name }}"
                    />
                    <div class="ml-4">
                        <h3 class="font-heading text-2xl font-bold leading-6 text-gray-900 dark:text-white sm:truncate">
                            {{ $customer->full_name }}
                        </h3>
                        <div class="mt-2 flex items-center sm:space-x-2">
                            <div class="flex items-center">
                                @if ($customer->email_verified_at)
                                    <x-untitledui-check-verified-02 class="size-5 text-green-500" aria-hidden="true" />
                                @else
                                    <x-untitledui-alert-circle class="size-5 text-danger-500" aria-hidden="true" />
                                @endif
                                <span class="ml-1.5 text-sm leading-5 text-gray-500 dark:text-gray-400">
                                    {{ $customer->email }}
                                </span>
                            </div>
                            <svg
                                viewBox="0 0 2 2"
                                class="hidden h-0.5 w-0.5 flex-none fill-gray-300 dark:fill-gray-500 sm:block"
                                aria-hidden="true"
                            >
                                <circle cx="1" cy="1" r="1" />
                            </svg>
                            <p class="hidden text-sm leading-5 text-gray-500 dark:text-gray-400 sm:block">
                                {{ __('shopper::pages/customers.period', ['period' => $customer->created_at->diffForHumans()]) }}
                            </p>
                        </div>
                    </div>
                </div>
            </div>
            <div class="mt-4 hidden space-x-2 lg:ml-4 lg:mt-0 lg:flex">
                <div>
                    <x-filament-actions::group
                        :actions="[
                            $this->deleteAction,
                        ]"
                        :label="__('shopper::forms.actions.more_actions')"
                        icon="heroicon-m-ellipsis-vertical"
                        dropdown-width="sh-dropdown-width"
                        color="gray"
                        size="sm"
                        :button="true"
                    />

                    <div x-data>
                        <template x-teleport="body">
                            <x-filament-actions::modals />
                        </template>
                    </div>
                </div>
            </div>
        </div>
    </x-shopper::container>

    <div class="relative">
        <div class="sticky top-0">
            <div class="border-t border-gray-200 dark:border-white/10">
                <x-filament::tabs :contained="true">
                    <x-filament::tabs.item alpine-active="currentTab === 'profile'" x-on:click="currentTab = 'profile'">
                        {{ __('shopper::pages/customers.profile.title') }}
                    </x-filament::tabs.item>
                    <x-filament::tabs.item alpine-active="currentTab === 'address'" x-on:click="currentTab = 'address'">
                        {{ __('shopper::pages/customers.addresses.title') }}
                    </x-filament::tabs.item>
                    <x-filament::tabs.item alpine-active="currentTab === 'orders'" x-on:click="currentTab = 'orders'">
                        {{ __('shopper::pages/orders.menu') }}
                    </x-filament::tabs.item>
                </x-filament::tabs>
            </div>
        </div>

        <div class="mt-10">
            <div x-show="currentTab === 'profile'">
                <livewire:shopper-customers.profile :$customer />
            </div>
            <div x-cloak x-show="currentTab === 'address'">
                <livewire:shopper-customers.addresses :$customer />
            </div>
            <div x-cloak x-show="currentTab === 'orders'">
                <livewire:shopper-customers.orders :$customer />
            </div>
        </div>
    </div>
</div>
