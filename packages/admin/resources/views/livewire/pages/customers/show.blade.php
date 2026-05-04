<div x-data="{ currentTab: $wire.entangle('activeTab') }" class="py-5">
    @php
        $stats = $this->stats;
        $defaultAddress = $this->defaultAddress;
        $hasOrders = $stats['orders_count'] > 0;
    @endphp

    <x-shopper::container class="space-y-8">
        @php
            $isActive = $hasOrders;
            $prevCustomer = $this->prevCustomer;
            $nextCustomer = $this->nextCustomer;
        @endphp
        <div class="lg:flex lg:items-center lg:justify-between">
            <div class="min-w-0 flex-1">
                <div class="flex items-center gap-4">
                    <img
                        class="size-14 rounded-full object-cover"
                        src="{{ $customer->picture }}"
                        alt="{{ $customer->full_name }}"
                    />
                    <div class="min-w-0">
                        <h3 class="font-heading text-sh-fg truncate text-2xl leading-7 font-bold">
                            {{ $customer->full_name }}
                        </h3>
                        <div class="text-sh-fg-secondary mt-1 flex flex-wrap items-center gap-x-2 gap-y-1 text-sm">
                            <x-filament::badge
                                :color="$isActive ? 'success' : 'gray'"
                                :icon="$isActive ? 'untitledui-check-verified-02' : 'untitledui-user-x-02'"
                                size="sm"
                            >
                                {{ $isActive
                                    ? __('shopper::pages/customers.header.active')
                                    : __('shopper::pages/customers.header.inactive') }}
                            </x-filament::badge>
                            <span aria-hidden="true" class="text-sh-fg-muted">·</span>
                            <span>
                                {{ __('shopper::pages/customers.header.id', ['id' => $customer->id]) }}
                            </span>
                            @if ($hasOrders)
                                <span aria-hidden="true" class="text-sh-fg-muted">·</span>
                                <span class="text-sh-fg font-medium">
                                    {{ shopper_money_format($stats['ltv']) }}
                                </span>
                                <span aria-hidden="true" class="text-sh-fg-muted">·</span>
                                <span>
                                    {{ trans_choice('shopper::pages/customers.header.orders_count', $stats['orders_count'], ['count' => $stats['orders_count']]) }}
                                </span>
                                @if ($stats['last_order_at'])
                                    <span aria-hidden="true" class="text-sh-fg-muted">·</span>
                                    <span>
                                        {{ __('shopper::pages/customers.header.last_order', [
                                            'time' => $stats['last_order_at']->diffForHumans(),
                                        ]) }}
                                    </span>
                                @endif
                            @endif
                        </div>
                    </div>
                </div>
            </div>
            <div class="mt-4 flex shrink-0 items-center gap-3 lg:mt-0 lg:ml-4">
                {{ $this->anonymizeAction }}

                <span class="relative z-0 inline-flex">
                    <button
                        @if ($prevCustomer) wire:click="goToCustomer({{ $prevCustomer->id }})" @endif
                        type="button"
                        @class([
                            'focus:shadow-outline-primary focus:border-primary-300 relative inline-flex items-center rounded-l-lg border border-gray-300 px-2 py-2 text-sm font-medium text-gray-500 transition duration-150 ease-in-out hover:text-gray-400 focus:z-10 focus:outline-none dark:border-white/10 dark:bg-gray-800 dark:text-gray-400 dark:hover:text-gray-500',
                            'bg-gray-50 disabled:cursor-not-allowed disabled:opacity-50' => ! $prevCustomer,
                            'bg-white' => $prevCustomer,
                        ])
                        aria-label="{{ __('shopper::pages/customers.header.previous') }}"
                        @if (! $prevCustomer) disabled @endif
                    >
                        <x-untitledui-chevron-left class="size-5" stroke-width="1.5" aria-hidden="true" />
                    </button>
                    <button
                        @if ($nextCustomer) wire:click="goToCustomer({{ $nextCustomer->id }})" @endif
                        type="button"
                        @class([
                            'focus:shadow-outline-primary focus:border-primary-300 relative -ml-px inline-flex items-center rounded-r-lg border border-gray-300 px-2 py-2 text-sm font-medium text-gray-500 transition duration-150 ease-in-out hover:text-gray-400 focus:z-10 focus:outline-none dark:border-white/10 dark:bg-gray-800 dark:text-gray-400 dark:hover:text-gray-500',
                            'bg-gray-50 disabled:cursor-not-allowed disabled:opacity-50' => ! $nextCustomer,
                            'bg-white' => $nextCustomer,
                        ])
                        aria-label="{{ __('shopper::pages/customers.header.next') }}"
                        @if (! $nextCustomer) disabled @endif
                    >
                        <x-untitledui-chevron-right class="size-5" stroke-width="1.5" aria-hidden="true" />
                    </button>
                </span>
            </div>
        </div>

    </x-shopper::container>

    {{ shopper()->getRenderHook(\Shopper\View\CustomerRenderHook::SHOW_HEADER_AFTER) }}

    <div class="mt-10 lg:grid lg:grid-cols-3 lg:gap-6">
        <div class="space-y-4 lg:col-span-2">
            <div>
                {{ shopper()->getRenderHook(\Shopper\View\CustomerRenderHook::SHOW_TABS_BEFORE) }}

                <x-filament::tabs class="sh-tabs-underline">
                    <x-filament::tabs.item alpine-active="currentTab === 'profile'" x-on:click="currentTab = 'profile'">
                        {{ __('shopper::pages/customers.profile.title') }}
                    </x-filament::tabs.item>
                    <x-filament::tabs.item alpine-active="currentTab === 'orders'" x-on:click="currentTab = 'orders'">
                        {{ __('shopper::pages/orders.menu') }}
                    </x-filament::tabs.item>
                    <x-filament::tabs.item alpine-active="currentTab === 'address'" x-on:click="currentTab = 'address'">
                        {{ __('shopper::pages/customers.addresses.title') }}
                    </x-filament::tabs.item>

                    {{ shopper()->getRenderHook(\Shopper\View\CustomerRenderHook::SHOW_TABS_END) }}
                </x-filament::tabs>
            </div>

            <div>
                {{ shopper()->getRenderHook(\Shopper\View\CustomerRenderHook::SHOW_CONTENT_BEFORE) }}

                <div x-show="currentTab === 'profile'">
                    <livewire:shopper-customers.profile :$customer :key="'profile-'.$customer->id" />
                </div>
                <div x-cloak x-show="currentTab === 'address'">
                    <livewire:shopper-customers.addresses :$customer :key="'addresses-'.$customer->id" />
                </div>
                <div x-cloak x-show="currentTab === 'orders'">
                    <livewire:shopper-customers.orders :$customer :key="'orders-'.$customer->id" />
                </div>

                {{ shopper()->getRenderHook(\Shopper\View\CustomerRenderHook::SHOW_CONTENT_AFTER) }}
            </div>
        </div>

        <aside class="mt-6 space-y-4 px-4 lg:sticky lg:top-4 lg:col-span-1 lg:mt-0 lg:self-start lg:pr-6">
            {{ shopper()->getRenderHook(\Shopper\View\CustomerRenderHook::SHOW_SIDEBAR_BEFORE) }}

            <x-shopper::card class="[&>div:first-of-type]:p-0">
                <div class="divide-y divide-gray-200 dark:divide-white/10">
                    <section class="space-y-3 p-4">
                        <h4 class="text-sh-fg text-sm font-semibold">
                            {{ __('shopper::pages/customers.details.title') }}
                        </h4>
                        <dl class="space-y-3 text-sm">
                            <div
                                class="flex items-center justify-between gap-3"
                                x-data="{ copied: false }"
                            >
                                <dt class="text-sh-fg-secondary">
                                    {{ __('shopper::pages/customers.details.id') }}
                                </dt>
                                <dd class="flex items-center gap-2">
                                    <span class="text-sh-fg font-mono text-xs tabular-nums">#{{ $customer->id }}</span>
                                    <button
                                        type="button"
                                        class="text-sh-fg-muted hover:text-sh-fg transition"
                                        x-on:click="
                                            navigator.clipboard.writeText('{{ $customer->id }}');
                                            copied = true;
                                            setTimeout(() => copied = false, 1500);
                                        "
                                        :aria-label="copied
                                            ? '{{ __('shopper::pages/customers.details.copied') }}'
                                            : '{{ __('shopper::pages/customers.details.copy_id') }}'"
                                    >
                                        <template x-if="!copied">
                                            <x-untitledui-copy class="size-4" aria-hidden="true" />
                                        </template>
                                        <template x-if="copied">
                                            <x-untitledui-check class="size-4 text-emerald-500" aria-hidden="true" />
                                        </template>
                                    </button>
                                </dd>
                            </div>
                            <div class="flex items-center justify-between gap-3">
                                <dt class="text-sh-fg-secondary">
                                    {{ __('shopper::pages/customers.details.created') }}
                                </dt>
                                <dd class="text-sh-fg">
                                    {{ $customer->created_at->translatedFormat('M j, Y') }}
                                </dd>
                            </div>
                            <div class="flex items-center justify-between gap-3">
                                <dt class="text-sh-fg-secondary">
                                    {{ __('shopper::pages/customers.details.email_status') }}
                                </dt>
                                <dd>
                                    <x-filament::badge :color="$customer->email_verified_at ? 'success' : 'warning'" size="sm">
                                        {{ $customer->email_verified_at
                                            ? __('shopper::pages/customers.details.email_verified')
                                            : __('shopper::pages/customers.details.email_unverified') }}
                                    </x-filament::badge>
                                </dd>
                            </div>
                            <div class="flex items-center justify-between gap-3">
                                <dt class="text-sh-fg-secondary">
                                    {{ __('shopper::pages/customers.profile.marketing') }}
                                </dt>
                                <dd>
                                    <x-filament::badge :color="$customer->opt_in ? 'success' : 'gray'" size="sm">
                                        {{ $customer->opt_in
                                            ? __('shopper::pages/customers.details.marketing_on')
                                            : __('shopper::pages/customers.details.marketing_off') }}
                                    </x-filament::badge>
                                </dd>
                            </div>
                            <div class="flex items-center justify-between gap-3">
                                <dt class="text-sh-fg-secondary">
                                    {{ __('shopper::pages/customers.profile.two_factor') }}
                                </dt>
                                <dd>
                                    <x-filament::badge :color="$customer->store_two_factor_secret ? 'success' : 'gray'" size="sm">
                                        {{ $customer->store_two_factor_secret
                                            ? __('shopper::pages/customers.details.two_factor_on')
                                            : __('shopper::pages/customers.details.two_factor_off') }}
                                    </x-filament::badge>
                                </dd>
                            </div>
                        </dl>
                    </section>

                    <section class="space-y-3 p-4">
                        <h4 class="text-sh-fg text-sm font-semibold">
                            {{ __('shopper::pages/customers.contact.title') }}
                        </h4>
                        <dl class="space-y-2 text-sm">
                            <div class="flex items-center gap-2">
                                <x-untitledui-mail class="text-sh-fg-muted size-4 shrink-0" aria-hidden="true" />
                                <a
                                    href="mailto:{{ $customer->email }}"
                                    class="text-sh-fg hover:text-primary-600 dark:hover:text-primary-400 truncate transition"
                                >
                                    {{ $customer->email }}
                                </a>
                            </div>
                            @if ($customer->phone_number)
                                <div class="flex items-center gap-2">
                                    <x-untitledui-phone class="text-sh-fg-muted size-4 shrink-0" aria-hidden="true" />
                                    <a
                                        href="tel:{{ $customer->phone_number }}"
                                        class="text-sh-fg hover:text-primary-600 dark:hover:text-primary-400 transition"
                                    >
                                        {{ $customer->phone_number }}
                                    </a>
                                </div>
                            @else
                                <p class="text-sh-fg-muted text-xs">
                                    {{ __('shopper::pages/customers.contact.no_phone') }}
                                </p>
                            @endif
                        </dl>
                    </section>

                    <section class="space-y-3 p-4">
                        <h4 class="text-sh-fg text-sm font-semibold">
                            {{ __('shopper::pages/customers.default_address.title') }}
                        </h4>
                        @if ($defaultAddress)
                            <div class="text-sh-fg space-y-1 text-sm">
                                <p class="font-medium">
                                    {{ $defaultAddress->full_name }}
                                </p>
                                <p class="text-sh-fg-secondary">
                                    {{ $defaultAddress->street_address }}
                                </p>
                                <p class="text-sh-fg-secondary">
                                    {{ $defaultAddress->postal_code }}, {{ $defaultAddress->city }}
                                </p>
                                @if ($defaultAddress->country)
                                    <p class="text-sh-fg-secondary inline-flex items-center gap-2">
                                        <img
                                            src="{{ $defaultAddress->country->svg_flag }}"
                                            class="size-4 rounded-full object-cover object-center"
                                            alt=""
                                        />
                                        {{ $defaultAddress->country->translated_name }}
                                    </p>
                                @endif
                            </div>
                        @else
                            <p class="text-sh-fg-muted text-sm">
                                {{ __('shopper::pages/customers.default_address.empty') }}
                            </p>
                        @endif
                    </section>
                </div>
            </x-shopper::card>

            {{ shopper()->getRenderHook(\Shopper\View\CustomerRenderHook::SHOW_SIDEBAR_AFTER) }}
        </aside>
    </div>

    <x-filament-actions::modals />
</div>
