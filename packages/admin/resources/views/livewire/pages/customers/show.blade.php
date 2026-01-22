<div x-data="{
    options: ['profile', 'address', 'orders'],
    currentTab: 'profile',
}" class="py-5">
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
                <div class="flex items-start gap-4">
                    <img
                        class="size-12 rounded-full object-cover"
                        src="{{ $customer->picture }}"
                        alt="{{ $customer->full_name }}"
                    />
                    <div>
                        <h3 class="font-heading text-2xl leading-6 font-bold text-gray-900 sm:truncate dark:text-white">
                            {{ $customer->full_name }}
                        </h3>
                        <div class="mt-2 flex items-center sm:space-x-2">
                            <div class="flex items-center gap-2">
                                @if ($customer->email_verified_at)
                                    <x-untitledui-check-verified-02
                                        class="text-success-500 size-5"
                                        aria-hidden="true"
                                    />
                                @else
                                    <x-untitledui-alert-circle class="text-danger-500 size-5" aria-hidden="true" />
                                @endif

                                <span class="text-sm text-gray-500 dark:text-gray-400">
                                    {{ $customer->email }}
                                </span>
                            </div>
                            <svg
                                viewBox="0 0 2 2"
                                class="hidden size-0.5 flex-none fill-gray-300 sm:block dark:fill-gray-500"
                                aria-hidden="true"
                            >
                                <circle cx="1" cy="1" r="1" />
                            </svg>
                            <p class="hidden text-sm text-gray-500 sm:block dark:text-gray-400">
                                {{ __('shopper::pages/customers.period', ['period' => $customer->created_at->diffForHumans()]) }}
                            </p>
                        </div>
                    </div>
                </div>
            </div>
            <div class="mt-4 hidden lg:mt-0 lg:ml-4 lg:flex">
                {{ $this->anonymizeAction }}
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
                <livewire:shopper-customers.profile :$customer :key="'profile-'.$customer->id" />
            </div>
            <div x-cloak x-show="currentTab === 'address'">
                <livewire:shopper-customers.addresses :$customer :key="'addresses-'.$customer->id" />
            </div>
            <div x-cloak x-show="currentTab === 'orders'">
                <livewire:shopper-customers.orders :$customer :key="'orders-'.$customer->id" />
            </div>
        </div>
    </div>

    <x-filament-actions::modals />
</div>
