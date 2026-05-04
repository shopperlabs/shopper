<x-shopper::container class="space-y-8 py-5">
    <div class="space-y-2">
        <x-shopper::heading :title="__('shopper::pages/customers.menu')">
            <x-slot name="action">
                @can('customers.create')
                    <x-filament::button tag="a" :href="route('shopper.customers.create')" wire:navigate>
                        {{ __('shopper::forms.actions.add_label', ['label' => __('shopper::pages/customers.single')]) }}
                    </x-filament::button>
                @endcan
            </x-slot>
        </x-shopper::heading>
        <p class="text-sh-fg-secondary max-w-2xl text-sm">
            {{ __('shopper::pages/customers.description') }}
        </p>
    </div>

    @php
        $stats = $this->stats;
        $hasCustomers = $stats['total'] > 0;
    @endphp

    <div class="grid grid-cols-1 gap-4 sm:grid-cols-2 lg:grid-cols-4">
        <x-shopper::card>
            <x-slot:title>
                <div class="flex items-center justify-between">
                    <span class="text-sh-fg-secondary text-sm font-medium">
                        {{ __('shopper::pages/customers.stats.total') }}
                    </span>
                    <x-phosphor-users-duotone class="text-sh-fg-secondary size-5" aria-hidden="true" />
                </div>
            </x-slot:title>

            <p class="font-heading text-sh-fg text-3xl font-bold">
                {{ \Illuminate\Support\Number::abbreviate($stats['total']) }}
            </p>

            <p class="text-sh-fg-muted mt-3 text-xs">
                {{ __('shopper::pages/customers.stats.total_subtitle') }}
            </p>
        </x-shopper::card>

        <x-shopper::card>
            <x-slot:title>
                <div class="flex items-center justify-between">
                    <span class="text-sh-fg-secondary text-sm font-medium">
                        {{ __('shopper::pages/customers.stats.new') }}
                    </span>
                    <x-phosphor-user-circle-plus-duotone class="text-sh-fg-secondary size-5" aria-hidden="true" />
                </div>
            </x-slot:title>

            <p class="font-heading text-sh-fg text-3xl font-bold">
                {{ $hasCustomers ? \Illuminate\Support\Number::abbreviate($stats['new_count']) : '—' }}
            </p>

            <p class="text-sh-fg-muted mt-3 text-xs">
                @if ($stats['new_count'] > 0)
                    <span class="font-medium text-emerald-600 dark:text-emerald-400">
                        +{{ \Illuminate\Support\Number::abbreviate($stats['new_count']) }}
                    </span>
                    {{ __('shopper::pages/customers.stats.new_30_days') }}
                @else
                    {{ __('shopper::pages/customers.stats.new_empty') }}
                @endif
            </p>
        </x-shopper::card>

        <x-shopper::card>
            <x-slot:title>
                <div class="flex items-center justify-between">
                    <span class="text-sh-fg-secondary text-sm font-medium">
                        {{ __('shopper::pages/customers.stats.active') }}
                    </span>
                    <x-phosphor-user-check-duotone class="text-sh-fg-secondary size-5" aria-hidden="true" />
                </div>
            </x-slot:title>

            <p class="font-heading text-sh-fg text-3xl font-bold">
                {{ $hasCustomers ? \Illuminate\Support\Number::abbreviate($stats['active_count']) : '—' }}
            </p>

            <p class="text-sh-fg-muted mt-3 text-xs">
                @if ($hasCustomers)
                    <span class="font-medium text-emerald-600 dark:text-emerald-400">
                            {{ $stats['active_percent'] }}%
                        </span>
                    {{ __('shopper::pages/customers.stats.active_subtitle') }}
                @else
                    {{ __('shopper::pages/customers.stats.active_empty') }}
                @endif
            </p>
        </x-shopper::card>

        <x-shopper::card>
            <x-slot:title>
                <div class="flex items-center justify-between">
                    <span class="text-sh-fg-secondary text-sm font-medium">
                        {{ __('shopper::pages/customers.stats.avg_ltv') }}
                    </span>
                    <x-phosphor-money-duotone class="text-sh-fg-secondary size-5" aria-hidden="true" />
                </div>
            </x-slot:title>

            <p class="font-heading text-sh-fg text-3xl font-bold">
                {{ $hasCustomers ? shopper_money_format($stats['avg_ltv']) : '—' }}
            </p>

            <p class="text-sh-fg-muted mt-3 text-xs">
                {{ $hasCustomers
                    ? __('shopper::pages/customers.stats.avg_ltv_subtitle')
                    : __('shopper::pages/customers.stats.avg_ltv_empty') }}
            </p>
        </x-shopper::card>
    </div>

    {{ shopper()->getRenderHook(\Shopper\View\CustomerRenderHook::INDEX_TABLE_BEFORE) }}

    <div>
        {{ $this->table }}
    </div>

    {{ shopper()->getRenderHook(\Shopper\View\CustomerRenderHook::INDEX_TABLE_AFTER) }}

    <x-shopper::learn-more :name="__('shopper::pages/customers.menu')" link="customers" />
</x-shopper::container>
